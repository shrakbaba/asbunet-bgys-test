# BGYS Test

Bu depo, **BGYS** projesinin test amaçlı GitHub sürümüdür.  
Yerel ortamdan (XAMPP) taşınan dosyaları içerir.

## Proje Durumu
Geliştirme/Test aşamasında.

## Kurulum (XAMPP)
1. Depoyu klonlayın:
   ```bash
   git clone https://github.com/shrakbaba/asbunet-bgys-test.git
   ```
2. Projeyi `htdocs` altına alın:
   - Windows örnek yol: `C:\xampp\htdocs\bgys`
3. XAMPP üzerinden **Apache** (ve gerekiyorsa **MySQL**) başlatın.
4. Tarayıcıdan projeyi açın:
   - `http://localhost/bgys`

## Git Akışı (ilk push sonrası)
```bash
git add .
git commit -m "Update project files"
git push origin main
```

## Notlar
- `.env`, yedekler, geçici dosyalar ve büyük medya dosyaları repoya eklenmemelidir.
- Gerekirse `.gitignore` dosyası projeye göre genişletilmelidir.
