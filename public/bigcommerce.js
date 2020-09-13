(function () {
    const params = parseParams();

    loadScript(params.account_id);
    linkCart(params.account_id, params.store_hash);

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

    function loadScript(accountId) {
        const customDomain = localStorage.getItem('bigcommerce_manychat_widget_domain');
        const defaultDomain = 'widgetdm.manychat.io';

        const mcScript = document.createElement("script");
        mcScript.src = 'https://' + (customDomain || defaultDomain) + '/' + accountId + '.js';

        document.body.append(mcScript);
    }

    function linkCart(accountId, storeHash) {
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

                    const query = '?cart_id=' + cartId + '&session_id=' + sessionId + '&account_id=' + accountId + '&store_hash=' + storeHash;
                    const request = new XMLHttpRequest();
                    request.open("GET", 'https://bigcommerce-manychat.site/big-commerce/link' + query, true);
                    request.send();

                    localStorage.setItem('bigcommerce_manychat_cart_linked', 'da');
                });
        };
    }
})();