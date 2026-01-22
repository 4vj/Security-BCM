# Youth Energy — Verbruiksmeter (Portfolio, fictief bedrijf)

Kort: demonstratie, webapp die een interactieve "verbruiksmeter" toont voor jongeren en jonge gezinnen. Youth Energy is een fictief bedrijf.

Functionaliteiten
- Interactieve verbruiksmeter met realtime of gesimuleerde data
- Dashboard met verbruiksgrafieken (dag/maand/jaar)
- Demo-pagina voor energiecontracten
- Responsive ontwerp

Technische stack
- Backend: PHP (zie composer/vendor)
- Frontend: HTML, CSS, JavaScript (Bootstrap gebruikt in [index.php](index.php))
- Static analysis / dev tools: phpstan config aanwezig ([phpstan.neon~](phpstan.neon~))
- Dependencies via Composer (zie map `vendor/`)

Installatie (lokale dev)
1. Clone de repo:S
   git clone <repo-url>
   cd secbcm
2. Installeer dependencies:
   composer install
3. Start lokale server (PHP built-in):
   php -S localhost:8000 -t .
4. Open http://localhost:8000

Navigeer naar belangrijke bestanden
- Project startpagina: [index.php](index.php)
- Intranet: [intranet/] (intranet/)
- LDAP-constanten en intranet-config: [intranet/utils/ldap_constants.inc.php](intranet/utils/ldap_constants.inc.php)  
- Project documentatie: [project documentation](project%20documentation/)  
- PHPStan config: [phpstan.neon~](phpstan.neon~)