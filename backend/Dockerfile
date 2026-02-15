# =========================
#  IMMAGINE BASE
# =========================

# Usa l'immagine ufficiale PHP versione 8.2 (CLI mode)
# CLI = Command Line Interface (non Apache)
FROM php:8.2-cli


# =========================
#  INSTALLAZIONE ESTENSIONI
# =========================

# Installa le estensioni necessarie per la connessione a MySQL
# - pdo → astrazione database
# - pdo_mysql → driver specifico per MySQL
RUN docker-php-ext-install pdo pdo_mysql


# =========================
#  DIRECTORY DI LAVORO
# =========================

# Imposta la directory di lavoro all'interno del container
# Tutti i comandi successivi verranno eseguiti dentro /app
WORKDIR /app


# =========================
#  COPIA FILE PROGETTO
# =========================

# Copia tutti i file della directory corrente
# (quella dove si trova il Dockerfile)
# dentro la cartella /app del container
COPY . .


# =========================
#  ESPOSIZIONE PORTA
# =========================

# Informa Docker che il container utilizzerà la porta 8000
# (non la apre automaticamente, serve per documentazione e mapping)
EXPOSE 8000


# =========================
#  COMANDO DI AVVIO
# =========================

# Avvia il server web integrato di PHP
# -S → start server
# 0.0.0.0 → ascolta su tutte le interfacce (necessario in Docker)
# 8000 → porta su cui il server sarà in ascolto
CMD ["php", "-S", "0.0.0.0:8000"]
