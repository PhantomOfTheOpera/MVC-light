/**
 * Created by killer on 08/09/16.
 */


var Ajax= {
    /**
     *
     * @param object - object
     * object.method
     * object.selector
     * object.address
     */
    send: function(object) {
        var xhr = new XMLHttpRequest();
        xhr.open(object.method, object.url, true);
        xhr.send(object.data);
        xhr.onreadystatechange = function() {
            if (xhr.status != 200)
                object.success(xhr.responseText);
            else
                object.error(xhr.responseText);
        }
    }
};
window.onload = function() {
    var forms = document.querySelectorAll('form.ajax_form');
    for (var i = 0; i < forms.length; i++) {
        forms[i].onsubmit = function(event) {
            event.preventDefault();
            var inputs = this.querySelectorAll('input.active'), query = '', form = this;
            for (var z = 0; z < inputs.length; z++)
                query += inputs[z].getAttribute('name') + '=' +  inputs[z].value + '&';
            query = query.slice(0, -1);
            Ajax.send({
                method: this.getAttribute('method'),
                url: this.getAttribute('url'),
                data: query,
                success: function(responce) {
                    window['form_' + form.id]['success'](responce);
                },
                error: function(responce){
                    window['form_' + form.id]['error'](responce);
                }
            });
        }
    }
};

form_RegForm = {

    success: function (text) {
        alert(text);
    },
    error: function (text) {
        console.log(text);
    }
};