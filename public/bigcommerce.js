(function () {
    const params = parseParams();

    loadScript("//widgetdm.manychat.io/" + params.account_id + ".js");

    linkCart();

    function parseParams() {
        const me = document.currentScript;

        if (!me || !me.src) {
            throw 'bigcommerce-manychat: current script not found';
        }

        const groups = me.src.match(/store_hash=(?<store_hash>.+?)&account_id=(?<account_id>[0-9]+)/).groups;

        if (!groups.store_hash || !groups.account_id) {
            throw 'bigcommerce-manychat: src not parsed';
        }

        return groups;
    }

    function loadScript(src) {
        const mcScript = document.createElement("script");
        mcScript.src = src;
        document.body.append(mcScript);
    }

    function linkCart() {
        if ('/checkout' !== location.pathname) {
            return;
        }

        if ('da' === localStorage.getItem('bigcommerce_manychat_cart_linked')) {
            return;
        }

        window.mcAsyncInit = function() {
            const sessionId = window.MC_PIXEL?.sessionData?.sessionId;
            if (!sessionId) {
                console.log('bigcommerce-manychat: no sessionId');
                return;
            }

            fetch('/api/storefront/cart')
                .then(function (response) {
                    return response.json();
                })
                .then(function (carts) {
                    const cartId = carts[0]?.id;

                    if (!cartId) {
                        return;
                    }

                    const url = 'https://bigcommerce-manychat.site/big-commerce/link?cart_id=' + cartId + '&session_id=' + sessionId;
                    const request = new XMLHttpRequest();
                    request.open("GET", url, true);
                    request.send();

                    localStorage.setItem('bigcommerce_manychat_cart_linked', 'da');
                });
        };
    }
})();