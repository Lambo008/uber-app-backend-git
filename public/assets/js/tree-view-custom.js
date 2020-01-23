$(document).ready(function () {
    // Basic
    $('#basicTree').jstree({
        'core': {
            'themes': {
                'responsive': false
            }
        },
        'types': {
            'default': {
                'icon': 'md md-folder'
            },
            'file': {
                'icon': 'md md-insert-drive-file'
            }
        },
        'plugins': ['types']
    });
    //checkbox
    $('#checkTree').jstree({
        'core': {
            'themes': {
                'responsive': false
            }
        },
        'types': {
            'default': {
                'icon': 'fa fa-folder'
            },
            'file': {
                'icon': 'fa fa-file'
            }
        },
        'plugins': ['types', 'checkbox']
    });
    // Drag & Drop
    $('#dragTree').jstree({
        'core': {
            'check_callback': true,
            'themes': {
                'responsive': false
            }
        },
        'types': {
            'default': {
                'icon': 'fa fa-folder'
            },
            'file': {
                'icon': 'fa fa-file'
            }
        },
        'plugins': ['types', 'dnd']
    });
});
