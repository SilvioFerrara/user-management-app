# User Management App (React + PHP + MySQL + Docker)

## Descrizione
Applicazione web per gestire una lista di utenti con:
- Nome
- Email
- Data di nascita

**Tecnologie utilizzate**:
- Frontend: React (Vite)
- Backend: PHP 8.2 con PDO e MySQL
- Database: MySQL
- Containerizzazione: Docker & Docker Compose

---

## Struttura progetto

```

user-app/
│
├── backend/        # PHP backend
│   ├── Dockerfile
│   ├── index.php
│   └── config/
│
├── frontend/       # React frontend
│   ├── Dockerfile
│   ├── package.json
│   └── src/
│
└── docker-compose.yml

````

---

## Prerequisiti

- Docker Desktop installato
- Port 5173 e 8000 libere

> Non serve installare PHP o MySQL sul PC: tutto è containerizzato.

---

## Avvio del progetto

1️⃣ Dalla root del progetto (`user-app`) avvia Docker Compose:

```bash
docker compose up --build
````

* Questo avvia **3 container**:

  * `react_frontend` → porta 5173
  * `php_backend` → porta 8000
  * `mysql_db` → porta 3306

2️⃣ Apri il browser:

* React: [http://localhost:5173](http://localhost:5173)
* PHP backend (test): [http://localhost:8000/index.php?action=list](http://localhost:8000/index.php?action=list)

---

## Configurazione database

MySQL già incluso in Docker.

* Database: `user_management`
* Utente root: `root`
* Password: `root`
* Porta: `3306`

### Creazione tabella utenti

All’interno del container MySQL:

```bash
docker exec -it mysql_db mysql -u root -p
# password: root

USE user_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    birthdate DATE NOT NULL
);
```

---

## Frontend

* Tutte le richieste al backend devono usare:

```js
fetch('http://localhost:8000/index.php?action=list')
```

* Per aggiungere un utente: `action=add` (POST JSON)

* In React si consiglia usare `.env`:

```
VITE_API_URL=http://localhost:8000
```

E poi in codice:

```js
const API = import.meta.env.VITE_API_URL;
fetch(`${API}/index.php?action=list`);
```

---

## Backend

* PHP 8.2 con PDO e MySQL
* File principale: `index.php`
* Database configurato in `config/Database.php`
* CORS già abilitato per il frontend (porta 5173)

---

## Comandi utili Docker

* Avvia tutto:

```bash
docker compose up --build
```

* Stop:

```bash
docker compose down
```

* Accedere al container PHP:

```bash
docker exec -it php_backend bash
```

* Accedere al container MySQL:

```bash
docker exec -it mysql_db mysql -u root -p
```

---
