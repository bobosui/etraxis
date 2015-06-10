/*!
 *  Copyright (C) 2012-2015 Artem Rodygin
 *
 *  This file is part of eTraxis.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
 */

(function($) {

    /**
     * Full-featured modal dialog.
     *
     * @returns {$.fn}
     */
    $.fn.modal = function(options) {

        var settings = $.extend({
            title: null,
            btnOk: eTraxis.i18n.Ok,
            btnCancel: eTraxis.i18n.Cancel,
            data: {},
            success: null,
            error: null
        }, options);

        var $modal = this;

        // Let the form be submitted via "ajaxForm" plugin.
        $('form', this).ajaxForm({

            cache: false,

            // Optional extra data to be submitted along with the form.
            data: settings.data,

            // Block the UI until AJAX query is completed.
            beforeSend: function() {
                eTraxis.block();
            },

            // When the AJAX query is done (no matter the result) - unblock the UI.
            complete: function() {
                eTraxis.unblock();
            },

            // Execute callback success handler if one is provided.
            success: function(data) {
                if (typeof settings.success === 'function') {
                    if (settings.success(data)) {
                        $modal.dialog('close');
                    }
                }
                else {
                    $modal.dialog('close');
                }
            },

            // Execute callback error handler if one is provided.
            error: function(xhr) {
                if (typeof settings.error === 'function') {
                    if (settings.error(xhr)) {
                        $modal.dialog('close');
                    }
                }
                else {
                    $modal.dialog('close');
                }
            }
        });

        var buttons = {};

        buttons[settings.btnOk] = function() {
            $('form', this).submit();
        };

        buttons[settings.btnCancel] = function() {
            $(this).dialog('close');
        };

        // Show the dialog.
        $(this).dialog({
            title: settings.title,
            autoOpen: true,
            modal: true,
            resizable: false,
            width: 'auto',
            height: 'auto',
            buttons: buttons,
            close: function() {
                $('form', this).each(function() {
                    $(this)[0].reset();
                });
            }
        });

        return this;
    };

}(jQuery));