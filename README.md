# user-management-app
applicazione full-stack PHP backend e React frontend
---

## 📌 Descrizione

La **User Management App** consente di visualizzare e aggiungere utenti tramite una semplice interfaccia web.  
Il backend espone una REST API in PHP, mentre il frontend React comunica con essa per la gestione dei dati.

I dati degli utenti vengono salvati in un file JSON.

---

## 🛠 Tecnologie Utilizzate

- **PHP** – Backend e API REST
- **React + Vite** – Frontend
- **JSON** – Persistenza dei dati

---

## 📁 Struttura del Progetto
user-management-app/
├── backend/
│ ├── index.php
│ └── users.json
├── frontend/
│ └── src/
│ ├── App.jsx
│ └── main.jsx
└── README.md


---

## ⚙️ Configurazione Backend (PHP)

1. Apri un terminale nella cartella `backend`
2. Avvia il server PHP:

```bash
php -S localhost:8000

Endpoint disponibili

GET /index.php?action=list → Restituisce la lista degli utenti

POST /index.php?action=add → Aggiunge un nuovo utente

⚙️ Configurazione Frontend (React)

Apri un terminale nella cartella frontend

Installa le dipendenze:

npm install


Avvia il server di sviluppo:

npm run dev


Il frontend sarà disponibile all’indirizzo:

http://localhost:5173