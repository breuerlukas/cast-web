class HttpRequest {
  constructor(url, method, headers, data) {
    this.url = url;
    this.method = method;
    this.headers = headers;
    this.data = data;
  }

  send(callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(this.method, this.url);
    for (const entry of this.headers) {
      xhr.setRequestHeader(entry.key, entry.value);
    }
    xhr.onload = function (e) {
      callback(this.status, xhr.responseText);
    };
    xhr.send(JSON.stringify(this.data));
  }
}