# =========================
#  IMMAGINE BASE
# =========================

# Usa l'immagine ufficiale Node.js versione 20
# Contiene Node.js e npm preinstallati
FROM node:20


# =========================
#  DIRECTORY DI LAVORO
# =========================

# Imposta /app come cartella di lavoro all'interno del container
# Tutti i comandi successivi verranno eseguiti dentro questa cartella
WORKDIR /app


# =========================
#  COPIA FILE DI CONFIGURAZIONE
# =========================

# Copia package.json e package-lock.json nel container
# Serve per installare le dipendenze senza copiare subito tutto il progetto
COPY package*.json ./


# =========================
#  INSTALLAZIONE DIPENDENZE
# =========================

# Installa tutte le dipendenze del progetto tramite npm
RUN npm install


# =========================
#  COPIA DEL PROGETTO
# =========================

# Copia tutti i file del progetto nella cartella /app del container
COPY . .


# =========================
#  ESPOSIZIONE PORTA
# =========================

# Informa Docker che il container utilizzerà la porta 5173
# (porta di default per Vite, il server di sviluppo React)
EXPOSE 5173


# =========================
#  COMANDO DI AVVIO
# =========================

# Avvia il server di sviluppo Vite
# "--host" permette di accedere al server dall'esterno del container
CMD ["npm", "run", "dev", "--", "--host"]

