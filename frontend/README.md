### Servidor Apache

# .htaccess

---

<IfModule mod_negotiation.c>
Options -MultiViews
</IfModule>

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /genesis/dist/
RewriteRule ^genesis/dist/index\.html$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /genesis/dist/index.html [L]
</IfModule>

«««««««««««««««««««««««««««««««««««««««««««««

# router > index.ts

---

const router = createRouter({
//history: createWebHistory(import.meta.env.BASE_URL),
history: createWebHistory('/genesis/dist/'),

// mode: process.env.VUE_APP_ROUTER_MODE_HISTORY === 'true' ? 'history' : 'hash',
routes,
})

««««««««««««««««««««««««««««««««««««««««««««««««

# vite.config.ts

---

base: '/genesis/dist/',
resolve: {
alias: {
'@': fileURLToPath(new URL('.src', import.meta.url)),
},
},

«««««««««««««««««««««««««««««««««««««««««««««««««««««««««

# index.html

---

<link
      rel="preload"
      as="style"
      onload="this.rel = 'stylesheet'"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700"
    />
    <link
      rel="preload"
      as="style"
      onload="this.rel = 'stylesheet'"
      href="https://fonts.googleapis.com/css?family=Source+Code+Pro:400"
    />
    <link
      rel="preload"
      as="style"
      onload="this.rel = 'stylesheet'"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link rel="icon" href="./favicon.ico" />

««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««

# global-store.ts

---

export const API_PATH = 'https://dvti.dyndns.org/flashwork'

«««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««

# dist/

---

Copy and paste ".htaccess" file inside dist folder

.htaccess (config)

«««««««««««««««««««««««««««««««

# rosemeri-beauty
