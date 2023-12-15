$.fn.toast = function (message = null, time) {
    this.id = this.id;
    this.message = message;
    this.time = time;
    this.show = false;
    this.type = 'success';

    this.showToast = (message, time = null, type = null) => {
        this.message = message;
        if (time) this.time = time;
        this.show = true;
        if (type) this.type = type;

        this.renderToast();
    }

    this.renderToast = () => {

        this.find('.toast-body').html(this.message);

        this.addClass(`bg-${this.type}`);

        if(this.show) {
            this.removeClass('hide');
            this.addClass('show');

            setTimeout( () => {
                this.addClass('hide');
                this.removeClass('show');
                this.show = false;
                this.removeClass(`bg-${this.type}`);
            }, this.time);
        }
    }

    return this;
}


class Loading {
    constructor(configs = {}, jqueryObj) {
        this.show = configs.show || false;
        this.jqueryObj = jqueryObj;
    }

    render = () => {
        let html = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        this.jqueryObj.html(html);
    }

    showLoading = (isShow) => {
        this.show = isShow;
        this.renderAfter();
    }

    renderAfter = () => {
        if(this.show) this.jqueryObj.addClass('show');
        else this.jqueryObj.removeClass('show')
    }
}

$.fn.loadding = function (configs = {}) {

    this.loadding = new Loading(configs, this);

    this.loadding.render();


    return this;
}