# 🎢 System Kolejek Górskich
Aplikacja API zarządzająca kolejkami górskimi, ich wagonami, personelem i przepustowością w czasie rzeczywistym. 
System informuje o problemach z obsługą klientów i generuje statystyki. 
Monitor CLI działa asynchronicznie dzięki ReactPHP.

## 🔧 Technologie
- PHP 8.2+
- CodeIgniter 4
- Redis
- ReactPHP (monitoring CLI)
- Docker + Docker Compose
- Nginx + PHP-FPM

## 📁 Struktura
- `app/Application/` – logika biznesowa
- `app/System/` – warstwa frameworka (np. konfiguracje, filtry)
- `setup.bash` – skrypt do uzupełnienia danych testowych (`curl`)
- `writable/` – katalog na logi, cache i sesje (ignoringowany przez git)

## ▶️ Uruchomienie aplikacji

```bash
git clone https://github.com/adam-pawelczykk/system-kolejek.git
cd system-kolejek

# Uruchomienie kontenerów
docker compose up --build -d

# Uzupełnienie przykładowych danych
bash setup.bash
```

### Aplikacja będzie dostępna pod adresem:
http://localhost:8080

### Monitor CLI
```
docker compose exec app php app/UI/CLI/monitor.php
```

### Tryby środowiskowe
#### Patrz .env.dev / .env.prod
```
CI_ENVIRONMENT=development # lub production
REDIS_PREFIX=dev:          # lub prod:
```

### Testy
#### Testy obejmują reguły domenowe
```
docker compose exec app php vendor/bin/phpunit
```

### Monitor
```
docker compose up -d
docker compose exec app php spark monitor
```

### Logi
Logi są zapisywane w writable/logs/{ENVIRONMENT}/
W trybie produkcyjnym logują się tylko error i warning
W trybie deweloperskim logują się wszystkie poziomy

### Wymagania
Docker + Docker Compose
Port 8080 wolny
bash do uruchomienia setup.bash - automatycznie uzupełnia przykładowe dane
