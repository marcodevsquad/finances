class Errors {
    constructor() {
        this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any() {
        return Object.keys(this.errors).length > 0;
    }

    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    record(errors) {
        this.errors = errors;
    }

    clear(field) {
        if (field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};
    }
}


class Form {
    constructor(data, format = 'json') {
        this.originalData = data;
        this.multipart = format.toLowerCase() === 'multipart'

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
        this.submitting = false
    }

    data() {
        return this.multipart ? this.multipartData() : this.jsonData();
    }

    jsonData() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }

    multipartData() {
        let data = new FormData();

        for (let property in this.originalData) {
            if(typeof(this[property]) === 'object' && !(this[property] instanceof File)){
                for(let sub in this[property]){
                    data.append(`${property}[${sub}]`, this[property][sub]);
                }
            } else {
                data.append(property, this[property]);
            }
        }

        return data;
    }

    reset() {
        for (let field in this.originalData) {
            this[field] = '';
        }

        this.errors.clear();
    }

    post(url, fieldName = null) {
        return this.submit('post', url, fieldName);
    }

    put(url, fieldName = null) {
        return this.submit('put', url, fieldName);
    }

    patch(url, fieldName = null) {
        return this.submit('patch', url, fieldName);
    }

    delete(url, fieldName = null) {
        return this.submit('delete', url, fieldName);
    }

    submit(requestType, url, fieldName = null) {
        const data = fieldName ? { [fieldName]: this.data() } : this.data()
        this.submitting = true
        return new Promise((resolve, reject) => {
            axios[requestType](url, data)
                .then(response => {
                    this.submitting = false

                    resolve(response);
                })
                .catch(error => {
                    this.submitting = false
                    if (error.response.status === 422) this.onFail(error.response.data.errors);

                    reject(error.response);
                });
        });
    }

    onFail(errors) {
        this.errors.record(errors);
    }
}

export {
    Form,
    Errors
}
