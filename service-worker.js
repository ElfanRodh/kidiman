// Installing service worker
const CACHE_NAME = "kidiman-perang-trucuk";

/* Add relative URL of all the static content you want to store in
 * cache storage (this will help us use our app offline)*/
let resourcesToCache = [
  "https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css",
  "assets/modules/bootstrap/css/bootstrap.min.css",
  "assets/modules/fontawesome/css/all.min.css",
  "assets/style.css",
  "assets/modules/datatables/datatables.css",
  "assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css",
  "assets/modules/datatables/Responsive-2.2.1/css/responsive.bootstrap4.css",
  "assets/modules/select2/dist/css/select2.min.css",
  "assets/modules/izitoast/css/iziToast.min.css",
  "assets/css/style.css",
  "assets/css/components.css",
  "assets/css/custom.css",
  "assets/modules/jquery.min.js",
  "assets/modules/popper.js",
  "assets/modules/tooltip.js",
  "assets/modules/bootstrap/js/bootstrap.min.js",
  "assets/modules/nicescroll/jquery.nicescroll.min.js",
  "assets/modules/moment.min.js",
  "assets/js/stisla.js",
  "assets/modules/sweetalert/sweetalert.min.js",
  "assets/js/page/modules-sweetalert.js",
  "assets/modules/izitoast/js/iziToast.min.js",
  "assets/js/page/modules-toastr.js",
  "assets/modules/datatables/datatables.min.js",
  "assets/modules/jquery-ui/jquery-ui.min.js",
  "assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js",
  "assets/modules/datatables/Responsive-2.2.1/js/responsive.bootstrap4.js",
  "assets/modules/select2/dist/js/select2.full.min.js",
  "assets/js/scripts.js",
  "style/custom.js",
  "style/web/vendor/bootstrap/css/bootstrap.min.css",
  "style/web/vendor/icofont/icofont.min.css",
  "style/web/vendor/boxicons/css/boxicons.min.css",
  "style/web/vendor/remixicon/remixicon.min.css",
  "style/web/vendor/aos/aos.min.css",
  "style/web/css/style.css",
];

self.addEventListener("install", (e) => {
  e.waitUntil(
    caches
      .open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(resourcesToCache);
      })
      .then(self.skipWaiting())
  );
});

// Cache and return requests
self.addEventListener("fetch", function (event) {
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.open(CACHE_NAME).then((cache) => {
        return cache.match(event.request);
      });
    })
  );
});

// Update a service worker
const cacheWhitelist = [CACHE_NAME];
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches
      .keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheWhitelist.indexOf(cacheName) === -1) {
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => self.clients.claim())
  );
});

