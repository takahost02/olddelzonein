var CACHE_NAME = 'pwgen-cache-v1';
var urlsToCache = [
    './web-sw.js?v3',
    './web-manifest.json?v3',
    'assets/images/logo-40.png',   
];
// console.log('loading sw');

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) { 
                var x = cache.addAll(urlsToCache);
                // console.log('cache added');
                return x;
            })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) { 
                    if (response) {
                        return response;
                    }
                    return fetch(event.request);
                }
            )
    );
});