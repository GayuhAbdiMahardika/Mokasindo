# 📊 Diagram UML - Mokasindo

Folder ini berisi semua diagram UML dalam format PlantUML (.puml).

## 📁 Daftar File

| File | Deskripsi |
|------|-----------|
| [usecase.puml](usecase.puml) | Use Case Diagram - Semua aktor dan use case |
| [activity_lelang.puml](activity_lelang.puml) | Activity Diagram - Proses lelang lengkap |
| [activity_auth.puml](activity_auth.puml) | Activity Diagram - Registrasi & Login |
| [activity_bidding.puml](activity_bidding.puml) | Activity Diagram - Proses bidding |
| [sequence_bidding.puml](sequence_bidding.puml) | Sequence Diagram - Proses bidding |
| [sequence_approve.puml](sequence_approve.puml) | Sequence Diagram - Approve kendaraan |
| [sequence_payment.puml](sequence_payment.puml) | Sequence Diagram - Pembayaran deposit |
| [class_diagram.puml](class_diagram.puml) | Class Diagram - Model dan relasi |
| [cdm.puml](cdm.puml) | Conceptual Data Model |
| [pdm.puml](pdm.puml) | Physical Data Model |

## 🔧 Cara Render Diagram

### Option 1: VS Code Extension
1. Install extension **"PlantUML"** (jebbs.plantuml)
2. Buka file `.puml`
3. Tekan `Alt + D` untuk preview

### Option 2: Online
1. Buka https://www.plantuml.com/plantuml/
2. Copy-paste isi file `.puml`
3. Klik "Submit"

### Option 3: Command Line
```bash
# Install via Chocolatey (Windows)
choco install plantuml

# Generate PNG
plantuml usecase.puml

# Generate SVG
plantuml -tsvg usecase.puml

# Generate semua diagram
plantuml *.puml
```

### Option 4: Kroki (API)
```bash
# Generate via curl
curl -X POST https://kroki.io/plantuml/png -d @usecase.puml -o usecase.png
```

## 📋 Diagram Summary

### Use Case Diagram
- **3 Aktor Utama**: Guest, User, Admin
- **2 Sistem Eksternal**: Midtrans, Telegram
- **40+ Use Cases**

### Activity Diagrams
- Alur registrasi hingga pengiriman
- Proses bidding dengan validasi
- Integrasi payment gateway

### Sequence Diagrams
- Interaksi antar komponen sistem
- Flow pembayaran via Midtrans
- Auto-create auction saat approve

### Class Diagram
- 12 Model Classes
- Relationships dan methods

### CDM & PDM
- Conceptual: Entity-Relationship
- Physical: Table structure dengan data types
