# VOYARA TRAVEL BOOKING SYSTEM
### Project Documentation & Important Updates

---

## Project Structure
A professional overview of where everything lives.

```text
voyara/
├── .htaccess                  # Apache URL rewriting + security headers
├── database.sql               # Full MySQL schema + seed data
├── seed.php                   # Database seeder (Local testing ko lagi)
├── config/
│   ├── app.php                # App constants, paths, debug mode
│   ├── database.php           # DB credentials
│   └── bootstrap.php          # Autoloader, session, helpers
├── app/
│   ├── controllers/           # Business Logic
│   ├── models/                # Database Queries & Models
│   └── views/                 # UI Templates (PHP/HTML)
├── public/
│   └── index.php              # Main entry point (Router)
├── assets/
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript logic
└── uploads/                   # User images/receipts storage
```

---

##  Suggestions for hosting (Important!)

Hajur ko previous README ma AWS EC2 ra Apache ko setup dekheko theye. Mero suggestion mannu hunxa vane, **EC2 vanda Digital Ocean ma host gareko aalik cheaper huncha ra manage garna pani sajilo huncha.**

### Why skip AWS EC2 & Apache?
AWS EC2 ma manual setup garna complex hunxa, ra bills calculate garna garo huda binary spike huna sakxa. Apache pani aaja-bholi ko modern apps ko lagi RAM heavy hunxa.

###  Deep Comparison (AWS vs DigitalOcean)

| Feature | AWS EC2 (Apache + MySQL) | DigitalOcean (Nginx + MariaDB) | Result |
| :--- | :--- | :--- | :--- |
| **Setup Cost** | Complex billing (hidden costs) | Fixed pricing ($4/mo start) | **DO wins** |
| **Complexity** | High (Headache hunxa manual setup) | Low (Sajilo dashboard) | **DO wins** |
| **Performance** | Apache handles traffic slowly | Nginx is lightweight & fast | **Nginx wins** |
| **Database** | MySQL (RAM heavy, CPU load high) | MariaDB (Optimized for PHP) | **MariaDB wins** |
| **Maintenance** | Manual management dherai chainxa | Easy snapshots & monitoring | **DO wins** |

Aws le Ip ko, domain ko, ssl ko, database ko, server ko, security ko, backup ko, monitoring ko, etc dherai kura haru ko lagi extra charge garxa, jaba ki DO ma fixed price ma sab milxa.

###  Recommendation:
- **Nginx use garnu:** Apache le RAM dherai khanxa, Nginx modern ra fast xa.
- **MariaDB use garnu:** MySQL aajkal obselete vaisako. MariaDB is the best choice for PHP. Yesle application ko performance badhaune ra server cost ghataune kaam garxa.
- **Support chahiyema:** Yo stack host garna i can help. Hajur lai live demo chahine vaye just vannu hola. Ma yo app lai host garera dekhauthe but NDA ko issue le garena. I dont wanna make toruble for you.

---

##  Error in project (Fix gareko updates):

Maile codebase deeply analyze garda aalik errors haru fela pare (normal basic syntax issues). Environment change vayera hoina, bas lekhda xuteko kura haru fix gardeko xu:

### 1. ROOT_PATH Error
Application run garda `ROOT_PATH is already defined` error aauxa. Maile configuration ma guard check thapi-deko xu:
```php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../..');
}
```

### 2. Header & Footer Bug
Footer ra Header files ma PHP tags close vayeko theyena (trademark/watermark deko thau ma). Tesle garda HTML leak vayera design bigrerathyo. Maile closing tags thapera fix gardeko xu.

### 3. Admin Login (Case Sensitive issue)
Admin model ma email search garda character exact match hunu parne theyo. Aauta capital letter mismatch huda login hudaina theyo. User experience ko lagi maile yeslai **Case Insensitive** banai-deko xu. `Abcd@gmail.com` hale pani database le accept garxa.

---

## Changes maile gareko:

1.  **Naya Homepage:** Purano design lai maile aasti deko modern premium design le replace gardeko xu.
2.  **Real Data Integration:** Homepage ma paila dummy data theyo, aaile actual database logic use huna thalisakyo.
3.  **Local Seeder:** `seed.php` thapeko xu. Local ma testing garna development ko thau dummy data halxa (Production ma use nagarnu).

maile configs haru ma mero local hisab le change gareko xa,  like database config haru yeslai hajur le production hisab le garnu hola.  

---

## Contact & Feedback

Maile yo sab kura haru hajur lai criticise garna wa, hajur ko code theek xaina vaneko haina, hajur ko code dherai ramro xa. Bas aali aali errors ra suggestions haru share gareko matra. Yadi hajur lai kei offend vo vane extreme sorry, maile bas h help garna khojeko ho.

Files ko top ma hajur kai generic style and name preservation gareko xu.

*   **Developer:** Utsav Pokhrel
*   **Email:** [utsavpokhrel56@gmail.com](mailto:utsavpokhrel56@gmail.com)
*   **GitHub:** [github.com/utsav-56](https://github.com/utsav-56)
