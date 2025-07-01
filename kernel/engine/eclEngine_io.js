
class eclEngine_io {

    request(data = false, endpoint = 'main', path = true) {
        var request = new eclIo_request(data, endpoint, path);
        return request;
    }

}
