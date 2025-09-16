window.KiwiBootstrap = new (function() {
    var ww = window.innerWidth;
    var width = undefined;
    var contentWidth = undefined;
    var name = undefined;

    function update() {
        ww = window.innerWidth;

        var oldWidth = width;
        var oldContentWidth = contentWidth;
        var oldName = name;

        if (ww >= 1200) {
            width = 1140;
            contentWidth = 1110;
            name = 'xl';
        } else if (ww >= 992) {
            width = 960;
            contentWidth = 930;
            name = 'lg';
        } else if (ww >= 768) {
            width = 720;
            contentWidth = 690;
            name = 'md';
        } else if (ww >= 576) {
            width = 540;
            contentWidth = 510;
            name = 'sm';
        } else {
            width = ww;
            contentWidth = ww - 30;
            name = 'xs';
        }

        if (oldWidth === undefined || width !== oldWidth || contentWidth !== oldContentWidth || name !== oldName) {
            try {
                var event = new Event('kiwiBootstrapResize');
            } catch (e) {
                // IE sucks
                var event = document.createEvent('Event');
                event.initEvent('kiwiBootstrapResize', false, false);
            }
            event.containerData = {width: width, contentWidth: contentWidth, name: name};
            window.dispatchEvent(event)
        }
    }

    this.getContainerData = function() {
        return {width: width, contentWidth: contentWidth, name: name}
    };

    window.addEventListener('resize', update);
    // fire resize once to get the current values
    try {
        var event = new UIEvent('resize');
    } catch (e) {
        // IE sucks
        var event = document.createEvent('Event');
        event.initEvent('resize', false, false);
    }
    window.dispatchEvent(event);

    return this;
})();
