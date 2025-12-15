package main

import (
	"encoding/json"
	"fmt"
	"html/template"
	"log"
	"net/http"
	"strconv"
	"sync"
	"time"
)

// Models
type User struct {
	ID       int    `json:"id"`
	Name     string `json:"name"`
	Email    string `json:"email"`
	Password string `json:"password"`
}

type Ad struct {
	ID          int       `json:"id"`
	UserID      int       `json:"user_id"`
	Title       string    `json:"title"`
	Description string    `json:"description"`
	Price       float64   `json:"price"`
	Location    string    `json:"location"`
	Phone       string    `json:"phone"`
	Category    string    `json:"category"`
	Status      string    `json:"status"`
	Views       int       `json:"views"`
	Favorites   int       `json:"favorites"`
	CreatedAt   time.Time `json:"created_at"`
}

type Stats struct {
	TotalAds    int
	ActiveAds   int
	TotalViews  int
	TotalFavs   int
}

// In-memory database
var (
	users      = make(map[int]*User)
	ads        = make(map[int]*Ad)
	userIDSeq  = 1
	adIDSeq    = 1
	mu         sync.RWMutex
	currentUser *User
)

func init() {
	// Create demo user
	demoUser := &User{
		ID:       userIDSeq,
		Name:     "Demo User",
		Email:    "demo@olx.com",
		Password: "demo123",
	}
	users[userIDSeq] = demoUser
	currentUser = demoUser
	userIDSeq++

	// Create demo ads
	demoAds := []Ad{
		{
			ID: adIDSeq, UserID: 1, Title: "iPhone 13 Pro Max 256GB",
			Description: "iPhone 13 Pro Max warna biru, kondisi mulus seperti baru. Lengkap dengan box dan charger original.",
			Price: 12500000, Location: "Jakarta Selatan", Phone: "081234567890",
			Category: "Elektronik", Status: "active", Views: 125, Favorites: 15,
			CreatedAt: time.Now().Add(-24 * time.Hour),
		},
		{
			ID: adIDSeq + 1, UserID: 1, Title: "Honda Civic Turbo 2020",
			Description: "Honda Civic Turbo 2020, warna putih, km 25rb, service record lengkap, pajak panjang.",
			Price: 425000000, Location: "Surabaya", Phone: "081234567890",
			Category: "Kendaraan", Status: "active", Views: 89, Favorites: 8,
			CreatedAt: time.Now().Add(-48 * time.Hour),
		},
		{
			ID: adIDSeq + 2, UserID: 1, Title: "Laptop ASUS ROG Strix G15",
			Description: "Laptop gaming ASUS ROG Strix G15, RTX 3060, Ryzen 7, RAM 16GB, SSD 512GB. Kondisi mulus.",
			Price: 18000000, Location: "Bandung", Phone: "081234567890",
			Category: "Elektronik", Status: "active", Views: 67, Favorites: 12,
			CreatedAt: time.Now().Add(-72 * time.Hour),
		},
	}

	for _, ad := range demoAds {
		ads[ad.ID] = &ad
		adIDSeq++
	}
}

// Handlers
func dashboardHandler(w http.ResponseWriter, r *http.Request) {
	mu.RLock()
	defer mu.RUnlock()

	stats := Stats{}
	var recentAds []*Ad

	for _, ad := range ads {
		if ad.UserID == currentUser.ID {
			stats.TotalAds++
			if ad.Status == "active" {
				stats.ActiveAds++
			}
			stats.TotalViews += ad.Views
			stats.TotalFavs += ad.Favorites
			recentAds = append(recentAds, ad)
		}
	}

	// Sort by created date (simple bubble sort for demo)
	for i := 0; i < len(recentAds)-1; i++ {
		for j := 0; j < len(recentAds)-i-1; j++ {
			if recentAds[j].CreatedAt.Before(recentAds[j+1].CreatedAt) {
				recentAds[j], recentAds[j+1] = recentAds[j+1], recentAds[j]
			}
		}
	}

	// Limit to 5 recent ads
	if len(recentAds) > 5 {
		recentAds = recentAds[:5]
	}

	data := struct {
		User       *User
		Stats      Stats
		RecentAds  []*Ad
	}{
		User:      currentUser,
		Stats:     stats,
		RecentAds: recentAds,
	}

	tmpl := template.Must(template.New("dashboard").Parse(dashboardTemplate))
	tmpl.Execute(w, data)
}

func adsListHandler(w http.ResponseWriter, r *http.Request) {
	mu.RLock()
	defer mu.RUnlock()

	var userAds []*Ad
	for _, ad := range ads {
		if ad.UserID == currentUser.ID {
			userAds = append(userAds, ad)
		}
	}

	data := struct {
		User *User
		Ads  []*Ad
	}{
		User: currentUser,
		Ads:  userAds,
	}

	tmpl := template.Must(template.New("ads").Parse(adsListTemplate))
	tmpl.Execute(w, data)
}

func createAdHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method == "GET" {
		tmpl := template.Must(template.New("create").Parse(createAdTemplate))
		tmpl.Execute(w, currentUser)
		return
	}

	if r.Method == "POST" {
		r.ParseForm()
		price, _ := strconv.ParseFloat(r.FormValue("price"), 64)

		mu.Lock()
		newAd := &Ad{
			ID:          adIDSeq,
			UserID:      currentUser.ID,
			Title:       r.FormValue("title"),
			Description: r.FormValue("description"),
			Price:       price,
			Location:    r.FormValue("location"),
			Phone:       r.FormValue("phone"),
			Category:    r.FormValue("category"),
			Status:      "active",
			Views:       0,
			Favorites:   0,
			CreatedAt:   time.Now(),
		}
		ads[adIDSeq] = newAd
		adIDSeq++
		mu.Unlock()

		http.Redirect(w, r, "/ads", http.StatusSeeOther)
		return
	}
}

func deleteAdHandler(w http.ResponseWriter, r *http.Request) {
	id, _ := strconv.Atoi(r.URL.Query().Get("id"))
	
	mu.Lock()
	delete(ads, id)
	mu.Unlock()

	http.Redirect(w, r, "/ads", http.StatusSeeOther)
}

func toggleStatusHandler(w http.ResponseWriter, r *http.Request) {
	id, _ := strconv.Atoi(r.URL.Query().Get("id"))
	
	mu.Lock()
	if ad, exists := ads[id]; exists {
		if ad.Status == "active" {
			ad.Status = "inactive"
		} else {
			ad.Status = "active"
		}
	}
	mu.Unlock()

	http.Redirect(w, r, "/ads", http.StatusSeeOther)
}

func apiStatsHandler(w http.ResponseWriter, r *http.Request) {
	mu.RLock()
	defer mu.RUnlock()

	stats := Stats{}
	for _, ad := range ads {
		if ad.UserID == currentUser.ID {
			stats.TotalAds++
			if ad.Status == "active" {
				stats.ActiveAds++
			}
			stats.TotalViews += ad.Views
			stats.TotalFavs += ad.Favorites
		}
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(stats)
}

func main() {
	http.HandleFunc("/", dashboardHandler)
	http.HandleFunc("/ads", adsListHandler)
	http.HandleFunc("/ads/create", createAdHandler)
	http.HandleFunc("/ads/delete", deleteAdHandler)
	http.HandleFunc("/ads/toggle", toggleStatusHandler)
	http.HandleFunc("/api/stats", apiStatsHandler)

	fmt.Println("🚀 Server berjalan di http://localhost:8080")
	fmt.Println("📊 Dashboard: http://localhost:8080")
	fmt.Println("📝 User Demo: demo@olx.com | Password: demo123")
	fmt.Println("\nTekan CTRL+C untuk berhenti")
	
	log.Fatal(http.ListenAndServe(":8080", nil))
}

// HTML Templates
const dashboardTemplate = `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard OLX</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #002f34; color: white; padding: 20px 0; margin-bottom: 30px; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
        .nav a:hover { text-decoration: underline; }
        .welcome { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-card .number { font-size: 32px; font-weight: bold; }
        .stat-card.blue .number { color: #3498db; }
        .stat-card.green .number { color: #27ae60; }
        .stat-card.purple .number { color: #9b59b6; }
        .stat-card.red .number { color: #e74c3c; }
        .actions { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .actions h2 { margin-bottom: 15px; }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-right: 10px; }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; }
        .btn-secondary { background: #ecf0f1; color: #333; }
        .btn-secondary:hover { background: #bdc3c7; }
        .section { background: white; padding: 20px; border-radius: 8px; }
        .section h2 { margin-bottom: 20px; }
        .ad-item { border: 1px solid #e0e0e0; padding: 15px; border-radius: 6px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: start; }
        .ad-info h3 { margin-bottom: 5px; }
        .ad-price { color: #3498db; font-weight: bold; font-size: 18px; margin-bottom: 5px; }
        .ad-meta { color: #666; font-size: 14px; }
        .ad-stats { display: flex; gap: 15px; margin-top: 8px; font-size: 14px; color: #666; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .empty { text-align: center; padding: 40px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">🛒 OLX Dashboard</div>
            <div class="nav">
                <a href="/">Dashboard</a>
                <a href="/ads">Iklan Saya</a>
                <a href="/ads/create">Buat Iklan</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="welcome">
            <h1>👋 Selamat datang, {{.User.Name}}!</h1>
            <p style="color: #666; margin-top: 5px;">Kelola iklan Anda dengan mudah</p>
        </div>

        <div class="stats">
            <div class="stat-card blue">
                <h3>Total Iklan</h3>
                <div class="number">{{.Stats.TotalAds}}</div>
            </div>
            <div class="stat-card green">
                <h3>Iklan Aktif</h3>
                <div class="number">{{.Stats.ActiveAds}}</div>
            </div>
            <div class="stat-card purple">
                <h3>Total Dilihat</h3>
                <div class="number">{{.Stats.TotalViews}}</div>
            </div>
            <div class="stat-card red">
                <h3>Total Favorit</h3>
                <div class="number">{{.Stats.TotalFavs}}</div>
            </div>
        </div>

        <div class="actions">
            <h2>🚀 Aksi Cepat</h2>
            <a href="/ads/create" class="btn btn-primary">+ Buat Iklan Baru</a>
            <a href="/ads" class="btn btn-secondary">📋 Lihat Semua Iklan</a>
        </div>

        <div class="section">
            <h2>📌 Iklan Terbaru</h2>
            {{if .RecentAds}}
                {{range .RecentAds}}
                <div class="ad-item">
                    <div class="ad-info">
                        <h3>{{.Title}}</h3>
                        <div class="ad-price">Rp {{printf "%.0f" .Price}}</div>
                        <div class="ad-meta">{{.Location}} • {{.Category}}</div>
                        <div class="ad-stats">
                            <span>👁️ {{.Views}} views</span>
                            <span>❤️ {{.Favorites}} favorit</span>
                        </div>
                    </div>
                    <div>
                        {{if eq .Status "active"}}
                        <span class="badge badge-success">Aktif</span>
                        {{else}}
                        <span class="badge badge-secondary">Tidak Aktif</span>
                        {{end}}
                    </div>
                </div>
                {{end}}
            {{else}}
                <div class="empty">
                    <p>📦 Belum ada iklan. Mulai buat iklan pertama Anda!</p>
                </div>
            {{end}}
        </div>
    </div>
</body>
</html>
`

const adsListTemplate = `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Iklan - OLX</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #002f34; color: white; padding: 20px 0; margin-bottom: 30px; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
        .nav a:hover { text-decoration: underline; }
        .section { background: white; padding: 20px; border-radius: 8px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; }
        .btn-primary { background: #3498db; color: white; }
        .btn-danger { background: #e74c3c; color: white; font-size: 12px; padding: 6px 12px; }
        .btn-warning { background: #f39c12; color: white; font-size: 12px; padding: 6px 12px; margin-right: 5px; }
        .ad-card { border: 1px solid #e0e0e0; padding: 20px; border-radius: 8px; margin-bottom: 15px; }
        .ad-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px; }
        .ad-title { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .ad-price { color: #3498db; font-weight: bold; font-size: 22px; margin-bottom: 10px; }
        .ad-description { color: #666; margin-bottom: 10px; line-height: 1.5; }
        .ad-meta { color: #999; font-size: 14px; margin-bottom: 10px; }
        .ad-stats { display: flex; gap: 20px; font-size: 14px; color: #666; margin-bottom: 15px; }
        .ad-actions { display: flex; gap: 10px; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .empty { text-align: center; padding: 60px 20px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">🛒 OLX Dashboard</div>
            <div class="nav">
                <a href="/">Dashboard</a>
                <a href="/ads">Iklan Saya</a>
                <a href="/ads/create">Buat Iklan</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <div class="section-header">
                <h2>📝 Daftar Iklan Saya</h2>
                <a href="/ads/create" class="btn btn-primary">+ Buat Iklan Baru</a>
            </div>

            {{if .Ads}}
                {{range .Ads}}
                <div class="ad-card">
                    <div class="ad-header">
                        <div style="flex: 1;">
                            <div class="ad-title">{{.Title}}</div>
                            <div class="ad-price">Rp {{printf "%.0f" .Price}}</div>
                        </div>
                        <div>
                            {{if eq .Status "active"}}
                            <span class="badge badge-success">✓ Aktif</span>
                            {{else}}
                            <span class="badge badge-secondary">⏸ Tidak Aktif</span>
                            {{end}}
                        </div>
                    </div>
                    
                    <div class="ad-description">{{.Description}}</div>
                    
                    <div class="ad-meta">
                        📍 {{.Location}} • 📱 {{.Phone}} • 🏷️ {{.Category}}
                    </div>
                    
                    <div class="ad-stats">
                        <span>👁️ {{.Views}} views</span>
                        <span>❤️ {{.Favorites}} favorit</span>
                        <span>📅 {{.CreatedAt.Format "02 Jan 2006"}}</span>
                    </div>
                    
                    <div class="ad-actions">
                        <a href="/ads/toggle?id={{.ID}}" class="btn btn-warning">
                            {{if eq .Status "active"}}⏸ Nonaktifkan{{else}}▶️ Aktifkan{{end}}
                        </a>
                        <a href="/ads/delete?id={{.ID}}" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus iklan ini?')">🗑️ Hapus</a>
                    </div>
                </div>
                {{end}}
            {{else}}
                <div class="empty">
                    <h3>📦 Belum ada iklan</h3>
                    <p style="margin-top: 10px;">Mulai buat iklan pertama Anda sekarang!</p>
                    <a href="/ads/create" class="btn btn-primary" style="margin-top: 20px;">+ Buat Iklan Baru</a>
                </div>
            {{end}}
        </div>
    </div>
</body>
</html>
`

const createAdTemplate = `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Iklan Baru - OLX</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: #002f34; color: white; padding: 20px 0; margin-bottom: 30px; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
        .nav a:hover { text-decoration: underline; }
        .form-section { background: white; padding: 30px; border-radius: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .btn { padding: 14px 28px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; font-size: 16px; }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; }
        .btn-secondary { background: #95a5a6; color: white; margin-left: 10px; }
        .btn-secondary:hover { background: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">🛒 OLX Dashboard</div>
            <div class="nav">
                <a href="/">Dashboard</a>
                <a href="/ads">Iklan Saya</a>
                <a href="/ads/create">Buat Iklan</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-section">
            <h2 style="margin-bottom: 25px;">📝 Buat Iklan Baru</h2>
            
            <form method="POST" action="/ads/create">
                <div class="form-group">
                    <label>Judul Iklan *</label>
                    <input type="text" name="title" required placeholder="Contoh: iPhone 13 Pro Max 256GB">
                </div>

                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="description" required placeholder="Jelaskan detail produk Anda..."></textarea>
                </div>

                <div class="form-group">
                    <label>Kategori *</label>
                    <select name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Elektronik">📱 Elektronik</option>
                        <option value="Kendaraan">🚗 Kendaraan</option>
                        <option value="Properti">🏠 Properti</option>
                        <option value="Fashion">👔 Fashion</option>
                        <option value="Hobi">🎮 Hobi & Olahraga</option>
                        <option value="Furniture">🛋️ Furniture</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp) *</label>
                    <input type="number" name="price" required placeholder="5000000" min="0">
                </div>

                <div class="form-group">
                    <label>Lokasi *</label>
                    <input type="text" name="location" required placeholder="Contoh: Jakarta Selatan">
                </div>

                <div class="form-group">
                    <label>Nomor Telepon *</label>
                    <input type="tel" name="phone" required placeholder="081234567890">
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">✓ Posting Iklan</button>
                    <a href="/ads" class="btn btn-secondary" style="text-decoration: none; display: inline-block;">✕ Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
`