# UPK
Uzņēmuma personāla kartotēka (DEMO).

## Stack
- PHP `^8.3` + Laravel `^13`
- Vite + TailwindCSS
- Vue 3 (interactive UI components)

## Setup (local)
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link

npm install
npm run dev
```

## Docker
If you want an automated Docker setup that runs the full project setup for you, use:
`https://github.com/aleksx108/upk-docker`

## Demo login
- Email: `admin@upk.lv`
- Password: `Password123`