echo "⏳ Tworzenie kolejek..."
curl -s -X POST http://localhost:8080/api/coasters \
  -H "Content-Type: application/json" \
  -d '{"id": "A1", "liczba_personelu": 11, "liczba_klientow": 200, "dl_trasy": 800, "godziny_od": "10:00", "godziny_do": "18:00"}'

curl -s -X POST http://localhost:8080/api/coasters \
  -H "Content-Type: application/json" \
  -d '{"id": "A2", "liczba_personelu": 5, "liczba_klientow": 150, "dl_trasy": 600, "godziny_od": "09:00", "godziny_do": "17:00"}'

curl -s -X POST http://localhost:8080/api/coasters \
  -H "Content-Type: application/json" \
  -d '{"id": "A3", "liczba_personelu": 10, "liczba_klientow": 100, "dl_trasy": 500, "godziny_od": "08:00", "godziny_do": "16:00"}'

echo "⏳ Dodawanie wagonów..."
for i in {1..5}; do
  curl -s -X POST http://localhost:8080/api/coasters/A1/wagons \
    -H "Content-Type: application/json" \
    -d "{\"wagon_id\": \"W$i\", \"ilosc_miejsc\": 32, \"predkosc_wagonu\": 1.2}"
done

for i in {1..3}; do
  curl -s -X POST http://localhost:8080/api/coasters/A2/wagons \
    -H "Content-Type: application/json" \
    -d "{\"wagon_id\": \"W$i\", \"ilosc_miejsc\": 32, \"predkosc_wagonu\": 1.2}"
done

for i in {1..2}; do
  curl -s -X POST http://localhost:8080/api/coasters/A3/wagons \
    -H "Content-Type: application/json" \
    -d "{\"wagon_id\": \"W$i\", \"ilosc_miejsc\": 32, \"predkosc_wagonu\": 1.2}"
done

echo "✅ Ukończono!"
