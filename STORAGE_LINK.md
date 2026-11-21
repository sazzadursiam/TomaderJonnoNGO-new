# cPanel-এ Storage Link (dev.tomaderjonno.org)

Laravel-এ upload করা ছবি (team, program, story ইত্যাদি) থাকে `storage/app/public`-এ। এগুলো সাইটে দেখাতে
`public/storage` নামে একটা link (symlink) লাগে। cPanel-এ Terminal না থাকায় এটা **Cron Job** দিয়ে একবার চালাতে হবে।

- **cPanel user:** `tomader1`
- **Laravel ফোল্ডার:** `/home/tomader1/dev.tomaderjonno.org`

---

## ধাপ ১: আগে পরীক্ষা করুন

File Manager → `dev.tomaderjonno.org/public` ফোল্ডারে ঢুকে দেখুন `storage` নামে কিছু আছে কি না।

| যা দেখবেন | কী করবেন |
|---|---|
| কিছু নেই | ধাপ ২-এ যান |
| `storage` আছে, link icon (তীর চিহ্ন) সহ | link আগেই আছে — ধাপ ৪-এ যান |
| `storage` আছে, সাধারণ ফোল্ডার | আগে এটা মুছুন বা নাম বদলান, তারপর ধাপ ২ |

---

## ধাপ ২: Cron Job যোগ করুন

1. cPanel-এর মূল পাতায় যান (উপরে বাম দিকে cPanel logo) → search-এ **Cron** লিখে **Cron Jobs** খুলুন।
2. **Add New Cron Job** অংশে:
   - **Common Settings:** `Once Per Minute (* * * * *)`
   - **Command:**
     ```
     cd /home/tomader1/dev.tomaderjonno.org && /usr/local/bin/php artisan storage:link >> /home/tomader1/storage-link.log 2>&1
     ```
3. **Add New Cron Job** চাপুন।

---

## ধাপ ৩: ফল দেখুন এবং Cron মুছে দিন

১–২ মিনিট পর File Manager-এ `/home/tomader1/storage-link.log` খুলুন।

- **`The [public/storage] link has been connected...`** লেখা থাকলে কাজ হয়ে গেছে।
  → **Cron Jobs পাতায় গিয়ে job-টা Delete করুন।** (না মুছলে প্রতি মিনিটে চলতে থাকবে।)
- **PHP version-এর error** (যেমন `requires PHP >= 8.3`) এলে command-এ `/usr/local/bin/php`-এর জায়গায় এগুলোর একটা দিয়ে চেষ্টা করুন:
  - `/opt/alt/php83/usr/bin/php`
  - `/opt/cpanel/ea-php83/root/usr/bin/php`
- **`already exists`** error এলে ধাপ ১ দেখুন।

একই উপায়ে অন্য artisan command-ও চালানো যায়, যেমন settings বদলানোর পর cache পরিষ্কার করতে:

```
cd /home/tomader1/dev.tomaderjonno.org && /usr/local/bin/php artisan cache:clear >> /home/tomader1/cache-clear.log 2>&1
```

---

## ধাপ ৪: ছবি upload ও যাচাই

ছবি upload করবেন **`storage/app/public/photos/...`**-এ (`public/storage`-এ নয়):

| ফোল্ডার | ছবি |
|---|---|
| `storage/app/public/photos/management-team/` | team-এর ৫টি ছবি |
| `storage/app/public/photos/education/` | 8.jpg – 15.jpg |
| `storage/app/public/photos/rehabilitation-support/` | 4.jpg – 6.jpg |
| `storage/app/public/photos/womens-empowerment/` | 30.jpg – 45.jpg |

যাচাই করতে browser-এ খুলুন:
`https://dev.tomaderjonno.org/storage/photos/management-team/md-forid-ahmed.jpg`
ছবি দেখালে link ঠিক আছে।

---

## নিরাপত্তা পরীক্ষা

- **`.env` বাইরে থেকে দেখা যায় কি না:** browser-এ `https://dev.tomaderjonno.org/.env` খুলুন। ফাইলের লেখা দেখা গেলে
  cPanel → **Domains** → subdomain-এর **Document Root** বদলে `/home/tomader1/dev.tomaderjonno.org/public` করুন।
- **Permission:** এখন সব ফোল্ডার `0777`, যা shared hosting-এ ঝুঁকিপূর্ণ। সাধারণ নিয়ম — ফোল্ডার `755`, ফাইল `644`;
  শুধু `storage` ও `bootstrap/cache`-এ লেখার অনুমতি লাগে।
