
# B2B Sipariş Yönetimi API

## 🧩 Senaryo
Bir B2B platformunda müşteri kullanıcılarının ürünleri görüntüleyip sipariş verebildiği basit bir sistem geliştirilmesi hedeflenmektedir.

---

## 👤 Kullanıcı Rolleri

| Rol      | Yetkiler                                                                 |
|----------|--------------------------------------------------------------------------|
| `admin`  | Tüm kullanıcıları ve siparişleri görebilir, ürün yönetebilir             |
| `customer` | Sadece kendi siparişlerini görebilir ve yeni sipariş oluşturabilir      |

---

## 📘 Modeller

### 1. User  
**Alanlar:** `name`, `email`, `password`, `role` (`admin` veya `customer`)

### 2. Product  
**Alanlar:** `name`, `sku`, `price`, `stock_quantity`

### 3. Order  
**Alanlar:** `user_id`, `status` (`pending`, `approved`, `shipped`), `total_price`

### 4. OrderItem (Pivot)  
**Alanlar:** `order_id`, `product_id`, `quantity`, `unit_price`


## 🐳 Docker

Projeyi Docker üzerinde çalıştırmak için aşağıdaki komutu projenin bulunduğu konumda çalıştırın.

```bash
  docker-compose up -d --build
```

```bash
  docker exec -it siparis_yonetim bash
```

```bash
  composer install
```

```bash
  php artisan key:generate
```

```bash
  php artisan migrate
```
---
Projeyi local bilgisayarınız üzerinde çalıştırmak için aşağıdaki komutu projenin bulunduğu konumda çalıştırın.

```bash
  composer install
```

```bash
  php artisan key:generate
```

```bash
  php artisan migrate:fresh --seed
```
---

## 🌐 Erişim
Postman || Tarayıcı : http://localhost:8000

---

## ⛹️‍♀️ Örnek Kullanıcılar, Siparişler ve Ürünler

Rastgele kullanıcılar, ürünler ve siparişler oluşturmak için komutu girin. 

```bash
 php artisan migrate:fresh --seed
```



## 🥨 API İstek Örnekleri
API istek örneklerinin bulunduğu dosya -> Sipariş Yönetim.postman_collection.json


## 
Laravel Resource sınıfları kullanılarak JSON response çıktılarının verilmesi sağlanmıştır.

## UYARI! 
```bash
phpMyAdmin Docker Container üzerinde mevcuttur. Rastgele oluşan kullanıcıları rahatlıkla kontrol edebilirsiniz.
Default password: password

.env dosyası oluşturup env.example dosyası içerisinde bulunan configleri kopyalayıp gerekli alanları uygun şekilde değiştirin.
```
