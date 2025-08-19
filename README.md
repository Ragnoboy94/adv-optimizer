@'
# Adv Optimizer —  (Laravel 11 + Vue 3 + Postgres + Docker)

Сервис автоматически оптимизирует параметры объявлений по гибким правилам. Пользователь задаёт условия (Spent/Clicks/Views/Budget/CPM) и действия (увеличить/уменьшить/set Budget/CPM). Система раз в 5 минут пересчитывает статистику и применяет изменения. Вся история фиксируется в логах. Есть UI для правил, логов и графика изменений.

---

## ⚙️ Стек
- **Backend:** Laravel 11 (PHP 8.3, Alpine)
- **Frontend:** Vue 3 + Vite
- **DB:** PostgreSQL 16 (Alpine)
- **Web:** Nginx (Alpine)
- **Scheduler:** `php artisan schedule:work` в отдельном контейнере

Порты по умолчанию: **7080** (Nginx), **5173** (Vite dev).

---

## Запуск

Требования: Docker Desktop.

```powershell
git clone https://github.com/ragnoboy94/adv-optimizer.git
cd adv-optimizer


# правим .env:
# APP_URL=http://127.0.0.1:7080
# DB_CONNECTION=pgsql
# DB_HOST=pg
# DB_PORT=5432
# DB_DATABASE=adv
# DB_USERNAME=adv
# DB_PASSWORD=adv
# QUEUE_CONNECTION=sync

docker compose build php scheduler
docker compose up -d pg php web node
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate --force
docker compose exec php php artisan db:seed --force

```
---

## Ручной запуск статистики
GET http://127.0.0.1:7080/api/run-once
