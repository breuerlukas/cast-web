importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

self.addEventListener("push", event => {
  var data = JSON.parse(event.data.text()).notification;
  self.registration.showNotification(data.title, {
    body: data.body,
    icon: data.icon
  });
});

self.addEventListener("notificationclick", event => {
  event.notification.close();
  event.waitUntil(clients.openWindow("https://lukasbreuer.de/"));
});