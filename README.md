<div align="center">

#  Clearè Skincare Shop

**Mini E-Commerce Kozmetike — Projekt Akademik**

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

> *Clearè* është një platformë e-commerce kozmetike e ndërtuar me PHP 8 dhe MySQL 8, si projekt akademik.  
> Ofron sistem autentikimi, shfletim produktesh, shportë blerjesh, checkout, panel administrativ dhe program besnikërie me pikë.

[Struktura](#-struktura-e-projektit) · [Instalimi](#-instalimi) · [Funksionalitetet](#-funksionalitetet) · [Databaza](#-databaza) · [Ekipi](#-ekipi)

</div>

---

##  Tabela e Përmbajtjes

- [Pamje e Përgjithshme](#-pamje-e-përgjithshme)
- [Tech Stack](#-tech-stack)
- [Struktura e Projektit](#-struktura-e-projektit)
- [Instalimi](#-instalimi)
- [Konfigurimi i Databazës](#-konfigurimi-i-databazës)
- [Funksionalitetet](#-funksionalitetet)
- [Paneli Administrativ](#-paneli-administrativ)
- [Databaza](#-databaza)
- [Kuponët e Zbritjes](#-kuponët-e-zbritjes)
- [Paleta e Ngjyrave & Dizajni](#-paleta-e-ngjyrave--dizajni)
- [Git Workflow](#-git-workflow)
- [Ekipi](#-ekipi)
- [Statusi i Projektit](#-statusi-i-projektit)

---

##  Pamje e Përgjithshme

**Clearè Skincare Shop** është një platformë e-commerce e plotë për produkte kozmetike dhe kujdesit të lëkurës.  
Projekti mbështet dy role kryesore — **customer** dhe **admin** — me flukse të ndara dhe të sigurta.

**Çfarë ofron platforma:**

- Shfletim dhe kërkim produktesh sipas kategorive (skincare, makeup, hair, body)
- Sistem shporte (session-based) për vizitorë dhe klientë të regjistruar
- Checkout me 3 metoda pagese (cash, PayPal dummy, Stripe dummy)
- Kuponë zbritjeje me validim të plotë (percent / fixed, me datë skadence)
- Program besnikërie — çdo 10L shpenzuar = 1 pikë
- Panel administrativ i plotë (produkte, porosi, kupona)
- Profil personal me historik porosish dhe pikëve

---

##  Tech Stack

| Teknologjia | Versioni | Përdorimi |
|-------------|----------|-----------|
| PHP | 8.x | Backend, logjika e serverit |
| MySQL | 8.x | Databaza relacionale |
| PDO | built-in | Komunikimi me databazën |
| HTML5 | — | Struktura e faqeve |
| CSS3 (Custom) | — | Stilizimi, ~2100 rreshta, 19 seksione |
| WAMP | localhost | Mjedisi i zhvillimit |
| Google Fonts | Cormorant Garamond + DM Sans | Tipografia |

> **Shënim:** Projekti nuk përdor framework të jashtëm PHP (pa Laravel, Symfony etj.). E gjithë logjika është e shkruar në PHP të pastër me PDO.

---

##  Struktura e Projektit

```
Cleare/
│
├── index.php                    # Faqja kryesore (homepage)
│
├── includes/
│   ├── db.php                   # Lidhja PDO me databazën (gitignored)
│   ├── auth.php                 # Funksionet e autentikimit
│   ├── cart_functions.php       # Funksionet e shportës
│   ├── products_db.php          # Funksionet për produktet (query DB)
│   ├── products.php             # Produkte statike (legacy)
│   ├── nav.php                  # Navigimi kryesor
│   ├── nav-js.php               # JavaScript i navigimit
│   └── footer.php               # Footer i faqes
│
├── pages/
│   ├── shop.php                 # Faqja e dyqanit me filtra & kërkim
│   ├── product.php              # Detajet e produktit të vetëm
│   ├── cart.php                 # Shporta e blerjeve
│   ├── checkout.php             # Faqja e pagesës
│   ├── order-confirm.php        # Konfirmimi i porosisë (CLR-XXXXX)
│   ├── profile.php              # Profili i klientit
│   ├── login.php                # Hyrja në llogari
│   ├── register.php             # Regjistrimi i llogarisë së re
│   ├── logout.php               # Dalja nga llogaria
│   ├── about.php                # Rreth nesh
│   ├── contact.php              # Kontakti
│   ├── terms.php                # Kushtet e përdorimit
│   └── admin/
│       ├── index.php            # Dashboard administrativ
│       ├── products.php         # CRUD produktesh
│       ├── orders.php           # Menaxhimi i porosive
│       ├── coupons.php          # Menaxhimi i kuponave
│       └── partials/
│           └── sidebar.php      # Sidebar i panelit admin
│
├── actions/
│   └── add-to-cart.php          # POST endpoint për shtimin në shportë
│
├── assets/
│   ├── css/
│   │   ├── style.css            # Stilizimi kryesor (~2100 rreshta)
│   │   └── admin.css            # Stilizimi i panelit admin
│   └── images/                  # Imazhet e produkteve (nuk janë në Git)
│
└── Database/
    └── clearedb_v4.sql          # Skema e databazës + të dhëna fillestare
```

---

##  Instalimi

### Kërkesat paraprake

- [WAMP](https://www.wampserver.com/) ose [XAMPP](https://www.apachefriends.org/) i instaluar
- PHP 8.0+
- MySQL 8.0+
- Shfletues modern (Chrome, Firefox, Edge)

### Hapat e instalimit

**1. Klonimi i repozitorit**

```bash
git clone https://github.com/piperoavi/Cleare.git
cd Cleare
```

**2. Vendosja e projektit**

Kopjo folderin `Cleare/` te direktoria e serverit:

```
# WAMP
C:\wamp64\www\Cleare\

# XAMPP
C:\xampp\htdocs\Cleare\
```

**3. Importimi i databazës**

Hap **phpMyAdmin** (`http://localhost/phpmyadmin`) dhe:

1. Krijo databazën `Cleare` (me **C të madhe** — case-sensitive!)
2. Zgjidh databazën `Cleare`
3. Shko te tab **Import**
4. Ngarko fajllin `Database/clearedb_v4.sql`
5. Kliko **Go**

```sql
-- Ose nga MySQL CLI:
mysql -u root -p
CREATE DATABASE Cleare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Cleare;
SOURCE /path/to/Database/clearedb_v4.sql;
```

**4. Konfigurimi i lidhjes me databazën**

Krijo fajllin `includes/db.php` (dërgohet veçmas nëpërmjet Discord, nuk gjendet në Git):

```php
<?php
$host   = 'localhost';
$dbname = 'Cleare';          //  C e madhe — case-sensitive!
$user   = 'root';
$pass   = '';                // Fjalëkalimi i MySQL-it tënd

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}
```

**5. Vendosja e imazheve**

Kopjo imazhet e produkteve te `assets/images/`. Emrat duhet të përputhen saktësisht me kolonën `image` në tabelën `products` të databazës.

**6. Hyrja në aplikacion**

Hap shfletuesin dhe shko te:

```
http://localhost/Cleare/
```

---

## 🗄 Konfigurimi i Databazës

>  **E rëndësishme:** Emri i databazës është `Cleare` me **C të madhe**. MySQL në Windows zakonisht është case-insensitive, por MySQL 8 në Linux/Mac është case-sensitive. Gjithmonë përdor `Cleare`.

### Kredencialet e paracaktuara

| Roli | Email | Fjalëkalimi |
|------|-------|-------------|
| Admin | `admin@cleare.al` | `admin123` |
| Customer (test) | `arta@gmail.com` | *(vendos tu importo DB)* |

> **Shënim:** Fjalëkalimet ruhen si hash bcrypt (`password_hash()` me `PASSWORD_BCRYPT`). Fjalëkalimet e demo-users janë `$2y$12$placeholder` në SQL — duhen rigjeneruar për testime reale.

---

##  Funksionalitetet

###  Autentikimi

| Funksioni | Përshkrimi |
|-----------|------------|
| `isLoggedIn()` | Kontrollon nëse sesioni ka `user_id` aktiv |
| `isAdmin()` | Kontrollon nëse `user_role === 'admin'` |
| `requireLogin()` | Ridrejton te `/pages/login.php` nëse jo i loguar |
| `requireAdmin()` | Ridrejton nëse jo admin (status 403) |

Sesioni ruan: `$_SESSION['user_id']`, `$_SESSION['user_name']`, `$_SESSION['user_role']`

```php
// Shembull përdorimi
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();     // Vetëm për faqe me të dhëna personale
requireAdmin();     // Vetëm për faqe admin
```

---

###  Shporta (Cart)

Shporta është **session-based** dhe funksionon si për vizitorët ashtu edhe për klientët e regjistruar.

| Funksioni | Përshkrimi |
|-----------|------------|
| `cart_init()` | Inicializon `$_SESSION['cart']` nëse nuk ekziston |
| `cart_add($id, $qty)` | Shton ose rrit sasinë e një produkti |
| `cart_remove($id)` | Heq një produkt nga shporta |
| `cart_update($id, $qty)` | Azhurnon sasinë e një produkti |
| `cart_count()` | Kthen numrin total të artikujve |
| `cart_get_items($pdo)` | Kthen array me produktet + detajet nga DB |
| `cart_subtotal($pdo)` | Llogarit totalin pa zbritje |
| `cart_clear()` | Zbraz shportën komplet |

> **Shënim:** Shporta humbet kur mbyllet shfletuesi (session-based). Persistence në DB mund të shtohet në të ardhmen.

---

###  Checkout

Procesi i blerjes:

1. **Forma e dërgimit** — prefill automatik nga profili i klientit (nëse i loguar)
2. **Metoda e pagesës** — `cash` / `paypal` (dummy) / `stripe` (dummy)
3. **Kuponi i zbritjes** — validim i plotë: aktiv, data, kategoria, 1 herë për NEWUSER
4. **Transaksioni PDO** — INSERT në `orders` + `order_items`, zvogëlim stoku
5. **Pikë besnikërie** — `floor(total / 10)` pikë shtohen te klienti
6. **Ridrejtim** te `order-confirm.php?order_id=X`

Numri i porosisë shfaqet si: `CLR-00001`, `CLR-00042` (str_pad me 5 shifra).

---

###  Profili i Klientit

- Ndryshim të dhënash personale: emër, telefon, adresë, qytet, gjini, datëlindje
- Historiku i porosive me statusin (pending → delivered)
- Kuponët e përdorur
- Pikët e akumuluara (loyalty points)
- Sidebar me navigim anchor links

---

##  Paneli Administrativ

> Aksesi kërkon `role_id = 1`. Çdo faqe fillon me `requireAdmin()`.

**URL:** `http://localhost/Cleare/pages/admin/`

### Dashboard (`admin/index.php`)

- Statistika totale: numri i porosive, produkteve, klientëve, revenue
- Porositë e fundit (recent orders)
- Alerta për produkte me stok të ulët (stock < 5)

### Produktet (`admin/products.php`)

- Listim i të gjithë produkteve me paginim
- Shto produkt të ri (me auto-generate slug)
- Ndrysho produkt ekzistues
- Fshi produkt

### Porositë (`admin/orders.php`)

- Listim i porosive me filtrim sipas statusit
- Ndryshim statusi me `<select>`: `pending → processing → shipped → delivered → cancelled`

### Kuponët (`admin/coupons.php`)

- Listim i kuponave me numrin e përdorimeve
- Shto kupon të ri (percent / fixed, me datë skadence)
- Toggle active/inactive me një klik

>  **Bug i njohur:** Sidebar i admin panel nuk shfaqet saktë për shkak të konfliktit CSS me `style.css`. Është në listën e rregullimeve.

---

## 🗃 Databaza

Databaza `Cleare` përmban **11 tabela**:

```
roles           → Rolet e sistemit (admin, customer, moderator)
users           → Të dhënat e klientëve dhe adminave
points_log      → Historia e ndryshimeve të pikëve
categories      → Kategoritë e produkteve (12 kategori)
products        → Katalogu i produkteve (30 produkte)
coupons         → Kuponët e zbritjes
coupon_usage    → Regjistrimi i kuponave të përdorura (UNIQUE per user+coupon)
orders          → Porositë e kryera
order_items     → Artikujt e çdo porosie (me GENERATED subtotal)
cart            → Shporta e ruajtur në DB (e lidhur me users)
search_history  → Historia e kërkimeve
```

### Relacionet kryesore

```
roles ──< users ──< orders ──< order_items >── products
                  ──< cart >── products
                  ──< coupon_usage >── coupons
                  ──< points_log
                  ──< search_history
         categories ──< products
         coupons ──< orders
```

### Enum values & CHECK constraints

| Tabela | Kolona | Vlerat e lejuara |
|--------|--------|-----------------|
| `users` | `gender` | `male`, `female`, `other`, NULL |
| `products` | `type` | `skincare`, `makeup`, `hair`, `body` |
| `orders` | `status` | `pending`, `processing`, `shipped`, `delivered`, `cancelled` |
| `orders` | `payment_method` | `stripe`, `paypal`, `cash` |
| `orders` | `payment_status` | `unpaid`, `paid`, `refunded` |
| `coupons` | `type` | `percent`, `fixed` |
| `coupons` | `applies_to` | `all`, `skincare`, `makeup`, `hair`, `body` |

---

##  Kuponët e Zbritjes

| Kodi | Tipi | Zbritja | Aplikohet te | Shënime |
|------|------|---------|--------------|---------|
| `CLEARE10` | Percent | 10% | Të gjithë | Gjithmonë aktiv |
| `NEWUSER` | Percent | 15% | Të gjithë | Vetëm klientë të regjistruar, vetëm herën e parë |
| `SKIN20` | Percent | 20% | Skincare | Vetëm produkte skincare |
| `HAIR15` | Percent | 15% | Hair | Vetëm produkte hair |
| `BODY10` | Fixed | -10L | Body | Zbritje fikse |
| `SUMMER26` | Percent | 25% | Të gjithë | Sezonal — skadon 31 Gusht 2026 |

---

##  Paleta e Ngjyrave & Dizajni

Stilizimi përdor **CSS Custom Properties** (variabla) të deklaruara te `:root` në `style.css`:

```css
:root {
  --sky:        #D6EEF5;   /* Blu e çelët — sfond */
  --sky-mid:    #A8D8EA;   /* Blu mesatare */
  --sky-deep:   #5BAFC9;   /* Blu e thellë — akcent kryesor */
  --green:      #B8D8C8;   /* Gjelbër e butë */
  --green-deep: #6FAE8E;   /* Gjelbër e thellë */
  --white:      #FAFCFD;   /* E bardhë e pastër */
  --cream:      #F0F7F4;   /* Krem — sfond dytësor */
  --ink:        #1E2A2E;   /* Tekst kryesor */
  --ink-soft:   #4A6070;   /* Tekst dytësor */
  --gold:       #A89070;   /* Dekorativ — detaje ari */
}
```

**Fontet (Google Fonts):**

| Font | Tipi | Përdorimi |
|------|------|-----------|
| `Cormorant Garamond` | Serif, elegante | Titujt, headings |
| `DM Sans` | Sans-serif, e lexueshme | Teksti i trupit |

---

##  Git Workflow

### Struktura e branches

```
main
 └── develop
      ├── feature/fjona    → Cart, Checkout, Profile, Order Confirm
      ├── feature/iva      → Frontend / UI Design
      ├── feature/donald   → Database + Auth
      ├── feature/endri    → Backend (Produktet nga DB)
      └── feature/elsa     → Admin Panel + Testing + README + Deploy
```

### Rregullat e detyrueshme

```bash
#  E saktë — fluksi i duhur
git checkout feature/emri
# ... bën ndryshime ...
git add .
git commit -m "feat: shto funksionin X"
git push origin feature/emri
# Hap PR: feature/emri → develop
# Pas review: develop → main (vetëm nga project lead)

#  Kurrë — ndalohet absolutisht
# PR direkt nga feature → main
# PR main → develop
# PR develop → feature (merge lokalisht, jo me PR)
```

### Nëse branch është behind

```bash
# Merge lokalisht — JO me PR nga GitHub
git checkout feature/emri
git fetch origin
git merge origin/develop
# Zgjidh konfliktet nëse ka
git push origin feature/emri
```

### Probleme historike & zgjidhjet

| Problemi | Si u zgjidh |
|----------|-------------|
| PR direkt te `main` nga `feature/endri`, `feature/elsa` | Rregulluar manualisht nga project lead |
| Elsa ngarkoi projektin si folder brenda repo-s (`Cleare-feature-elsa/`) | `git revert` + `git push --force` |
| Konflikte merge te `style.css` | VS Code Merge Editor (manual resolution) |

---

##  Ekipi

| Anëtar | Roli | Branch | Kontributi kryesor |
|--------|------|--------|--------------------|
| **Fjona** | Project Lead | `feature/fjona` | Cart, Checkout, Profile, Order Confirmation |
| **Iva** | Frontend Developer | `feature/iva` | UI/UX Design, HTML/CSS i të gjitha faqeve  |
| **Elsa** | QA & DevOps | `feature/elsa` | Admin Panel, Testing, README, Deployment |
| **Endri** | Backend Developer | `feature/endri` | Produktet nga DB (`products_db.php`, `shop.php`) |
| **Donald** | Database & Auth | `feature/donald` | Skema DB, Autentikimi, PDO sessions |

---

##  Statusi i Projektit

###  Të Gatshme

- [x] Autentikimi i plotë (login, register, logout, session)
- [x] Shfletimi i produkteve me filtra & kërkim
- [x] Shporta e blerjeve (session-based + badge dinamike)
- [x] Checkout me kuponë + metodat e pagesës
- [x] Konfirmimi i porosisë (CLR-XXXXX)
- [x] Profili i klientit me historik & pikë
- [x] Panel Administrativ (dashboard, produkte, porosi, kupona)
- [x] Frontend/UI — të gjitha faqet 

###  Në Progres / Mbeten

- [ ] **Imazhet e produkteve** — te `assets/images/` me emrat saktë (Iva)
- [ ] **Fix sidebar admin** — konflikt CSS me `style.css` (Elsa)
- [ ] **README.md** — ky dokument (Elsa) 
- [ ] **Testim final end-to-end** (Elsa)
- [ ] **Dorëzim ZIP** (Elsa)
- [ ] Deploy opsionale — InfinityFree / 000webhost + FileZilla FTP
- [ ] Stripe/PayPal real integration *(opsionale, +5 pikë bonus)*

---

##  Shënime të Sigurisë

- Fjalëkalimet ruhen me `password_hash()` (bcrypt, cost 12)
- Të gjithë query-t përdorin **PDO prepared statements** (mbrojtje nga SQL Injection)
- Kontrollet e roleve bëhen server-side (`requireLogin()`, `requireAdmin()`)
- `db.php` **nuk gjendet në Git** — dërgohet nëpërmjet kanalit të sigurt (Discord)
- Sesionet distrugjohen komplet me `session_destroy()` gjatë logout

---

##  Shënime Teknike

| Çështja | Detaji |
|---------|--------|
| DB Connection | Vetëm PDO — `mysqli` nuk përdoret kurrë |
| Include paths | Gjithmonë `__DIR__ . '/../../includes/...'` |
| Session start | `session_start()` në krye të çdo faqeje |
| DB name | `Cleare` — **C e madhe, case-sensitive** |
| Imazhet | Ruhen te `assets/images/` — në DB vetëm emri i fajllit |
| Stripe/PayPal | Dummy implementation — nuk kryhen pagesa reale |
| Cart persistence | Session-based — humbet kur mbyllet shfletuesi |
| Loyalty points | `floor(total / 10)` pikë për çdo porosi |
| Order number format | `CLR-` + `str_pad(id, 5, '0', STR_PAD_LEFT)` |

---

<div align="center">

Made with 🩵 by the Clearè Team  
*Projekt Akademik — PHP + MySQL*

</div>
