# 🍽️ TastyTalks – Recipe Management Platform

TastyTalks is a Laravel-powered web platform that lets users save, organize, and share their favorite recipes. With support for filtering by ingredients, cuisine, and difficulty level, plus step-by-step video tutorials, it's the perfect digital recipe book for food enthusiasts and home chefs alike.

## 🚀 Features

- 🧑‍🍳 User registration and authentication
- 📖 Create, edit, and delete recipes
- 📹 Step-by-step video tutorials
- 🔍 Filter/search by ingredients, cuisine type, or difficulty
- ❤️ Favorite and share recipes
- 🧾 Clean UI & responsive design

---

## 🛠️ Installation & Setup

### 1. Clone the Repository
    ```bash
    git clone https://github.com/your-username/tastytalks.git
    cd tastytalks

### 2.Install Dependencies
composer install
npm install && npm run dev

### 3. Configure Environment
cp .env.example .env
php artisan key:generate

Set your DB credentials in .env:
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

### 4. Run Migrations and Seeders
php artisan migrate --seed

### 5. Start the Server
php artisan serve

Visit: http://localhost:8000 🎉🎉
