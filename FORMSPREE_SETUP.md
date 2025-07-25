# Formspree Setup-Anleitung für Bastian Schwinn Website

## 🚀 Schnelle Einrichtung (5 Minuten)

### Schritt 1: Formspree-Konto erstellen
1. Gehen Sie zu [formspree.io](https://formspree.io)
2. Klicken Sie auf "Get Started" 
3. Registrieren Sie sich mit Ihrer E-Mail-Adresse
4. Bestätigen Sie Ihre E-Mail-Adresse

### Schritt 2: Neues Formular erstellen
1. Nach der Anmeldung klicken Sie auf "+ New Form"
2. **Form Name**: "Kontaktformular Website"
3. **Email**: `coaching-schwinn@gmx.de` (Ihre E-Mail-Adresse)
4. Klicken Sie auf "Create Form"

### Schritt 3: Form-ID kopieren
1. Sie sehen jetzt Ihre **Form-ID** (z.B. `mbjzklw1`)
2. Kopieren Sie diese ID

### Schritt 4: Website aktualisieren
1. Öffnen Sie `kontakt.html`
2. Suchen Sie die Zeile: `const formspreeURL = 'https://formspree.io/f/YOUR_FORM_ID';`
3. Ersetzen Sie `YOUR_FORM_ID` durch Ihre echte Form-ID
4. Suchen Sie: `<form id="contactForm" action="https://formspree.io/f/YOUR_FORM_ID" method="POST">`
5. Ersetzen Sie auch hier `YOUR_FORM_ID`

**Beispiel:**
```javascript
const formspreeURL = 'https://formspree.io/f/mbjzklw1';
```

```html
<form id="contactForm" action="https://formspree.io/f/mbjzklw1" method="POST">
```

### Schritt 5: Testen
1. Speichern Sie die Datei
2. Öffnen Sie die Website im Browser
3. Füllen Sie das Kontaktformular aus und senden Sie eine Test-E-Mail
4. Die erste E-Mail muss bestätigt werden (Formspree-Sicherheit)

## ✅ Fertig!

### Was Sie erhalten:
- **50 kostenlose E-Mails pro Monat**
- **DSGVO-konforme Verarbeitung**
- **Spam-Schutz integriert**
- **Keine Werbung in E-Mails**
- **Deutsche Server verfügbar**

### Optionale Einstellungen in Formspree:
- **Notifications**: E-Mail-Benachrichtigungen anpassen
- **Redirects**: Nach dem Senden zu einer Danke-Seite weiterleiten
- **Spam Protection**: Zusätzliche Spam-Filter aktivieren
- **Webhooks**: Für erweiterte Integrationen

### Support:
Bei Problemen können Sie mich gerne kontaktieren oder die Formspree-Dokumentation unter [help.formspree.io](https://help.formspree.io) besuchen.

---
**Wichtig**: Die Form-ID muss an beiden Stellen in der `kontakt.html` ersetzt werden!
