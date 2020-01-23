     $(document).ready(function () {
                $(function () {
                    var element = "[class*=col]";
                    var handle = ".card-title";
                    var connect = "[class*=col]";
                    $(element).sortable(
                            {
                                handle: handle,
                                connectWith: connect,
                                tolerance: 'pointer',
                                forcePlaceholderSize: true,
                                opacity: 0.8,
                                containment: '#sortable-view'
                            })
                            .disableSelection();
                });
            });