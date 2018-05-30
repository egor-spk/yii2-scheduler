Scheduler = {
    common: {
        scheduler: '#yii2-scheduler',
        tabContent: '#yii2-scheduler > .tab-content',
        spinner: '#yii2-scheduler > .spinner',
        navtabs: '#yii2-scheduler > .nav-tabs > li',
        prevRoute: '',

        init: function () {
            // load task list
            this.changeTabContent('tasks');

            // change tab content event
            $(this.navtabs).click(function (e) {
                e.preventDefault();
                var route = $(this).find('a').attr('href');
                if (Scheduler.common.tabIsChanged(route)) {
                    Scheduler.common.changeTabContent(route);
                }
            });
        },

        changeTabContent: function (route) {
            $(this.tabContent).hide().empty();
            $(this.spinner).fadeIn(200);
            Scheduler.common.prevRoute = route;
            $.ajax({
                    type: 'GET',
                    url: 'index.php?r=scheduler/' + route,
                    success: function (data) {
                        $(Scheduler.common.spinner).fadeOut(200, function () {
                            $(Scheduler.common.tabContent).html(data).fadeIn(200);
                        });
                    },
                    error: function (jqXHR) {
                        $(Scheduler.common.spinner).fadeOut(200, function () {
                            Scheduler.common.error(jqXHR.status);
                        });
                    }
                }
            );
        },

        tabIsChanged: function (route) {
            if (this.prevRoute.length === 0 || this.prevRoute !== route)
                return true;
            else
                return false;
        },

        error: function (status) {
            if (status === 404) {
                alert('Not found.');
            } else if (status === 400) {
                alert('Bad request.');
            } else if (status === 500) {
                alert('Internal server error.');
            }
            else {
                alert('Unknown error.');
            }
        }
    },

    tasks: {
        modal: '#yii2-scheduler #run-task-modal',

        init: function () {

        },

        onCreate: function () {
            Scheduler.common.changeTabContent('task-create');
            $(Scheduler.common.navtabs + '.active').removeClass('active');
            $(Scheduler.common.navtabs).find('[href=task-create]').parent().addClass('active');
        },

        // gridview actions
        onRun: function (id) {
            var $modal = $(this.modal);
            var $modalBody = $modal.find('.modal-body textarea');
            var $modalContent = $modal.find('.modal-content');

            // preparing and open
            $modalContent.removeClass('modal-ok').removeClass('modal-bad');
            $modalBody.text('Execution...');
            $modal.modal();

            $.ajax({
                    type: 'GET',
                    url: 'index.php?r=scheduler/run-task&id=' + id,
                    success: function (data) {
                        res = JSON.parse(data);
                        console.log(res);
                        if (res.status === true)
                            $modalContent.addClass('modal-ok');
                        else
                            $modalContent.addClass('modal-bad');
                        $modalBody.html(res.output);
                    },
                    error: function (jqXHR) {
                        $modal.modal('toggle');
                        Scheduler.common.error(jqXHR.status);
                    }
                }
            );
        },

        onView: function (id) {
            Scheduler.common.changeTabContent('task-view&id=' + id);
            $(Scheduler.common.navtabs + '.active').removeClass('active');
            $(Scheduler.common.navtabs).find('[href=task-create]').parent().addClass('active');
        },

        onEdit: function (id) {
            Scheduler.common.changeTabContent('task-update&id=' + id);
            $(Scheduler.common.navtabs + '.active').removeClass('active');
            $(Scheduler.common.navtabs).find('[href=task-create]').parent().addClass('active');
        },

        onLog: function (id) {
            Scheduler.common.changeTabContent('logs&task_id=' + id);
            $(Scheduler.common.navtabs + '.active').removeClass('active');
            $(Scheduler.common.navtabs).find('[href=logs]').parent().addClass('active');
        },

        onDelete:
            function (id) {
                if (confirm('Are you sure you want to delete this item?')) {
                    $.ajax({
                            type: 'POST',
                            url: 'index.php?r=scheduler/task-delete&id=' + id,
                            success: function (data) {
                                $.pjax.reload({container: '#grid-pjax', url: 'index.php?r=scheduler/tasks'});
                            },
                            error: function (jqXHR) {
                                Scheduler.common.error(jqXHR.status);
                            }
                        }
                    );
                }
            }
    },

    logs: {
        init: function () {

        },

        // gridview actions
        onDelete: function () {
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                        type: 'POST',
                        data: $('form#date-range').serialize(),
                        url: 'index.php?r=scheduler/logs-delete',
                        success: function (data) {
                            if (data != 0)
                                $.pjax.reload({container: '#grid-pjax', url: 'index.php?r=scheduler/logs'});
                            alert('Deleted ' + data + ' rows');
                        },
                        error: function (jqXHR) {
                            Scheduler.common.error(jqXHR.status);
                        }
                    }
                );
            }
        },

        onView: function (id) {
            $.ajax({
                    type: 'GET',
                    url: 'index.php?r=scheduler/log-output&id=' + id,
                    success: function (data) {
                        var $modal = $('#output-modal');
                        $modal.find('.modal-body textarea').empty().html(data);
                        $modal.modal();
                    },
                    error: function (jqXHR) {
                        Scheduler.common.error(jqXHR.status);
                    }
                }
            );
        },

        onTaskView: function (id) {
            Scheduler.tasks.onView(id);
        }
    },

    view: {
        init: function () {

        },

        // button actions
        onDelete:
            function (id) {
                if (confirm('Are you sure you want to delete this item?')) {
                    $.ajax({
                            type: 'POST',
                            url: 'index.php?r=scheduler/task-delete&id=' + id,
                            success: function (data) {
                                Scheduler.common.changeTabContent('tasks');
                                $(Scheduler.common.navtabs + '.active').removeClass('active');
                                $(Scheduler.common.navtabs).find('[href=tasks]').parent().addClass('active');
                            },
                            error: function (jqXHR) {
                                Scheduler.common.error(jqXHR.status);
                            }
                        }
                    );
                }
            },

        onEdit: function (id) {
            Scheduler.common.changeTabContent('task-update&id=' + id);
        },

        onLogs: function (id) {
            Scheduler.tasks.onLog(id);
        }
    },

    edit: {
        methods: '#yii2-scheduler #schedulertask-method',
        command: '#yii2-scheduler #schedulertask-command',

        init: function () {
            $(this.methods).change(function () {
                var className = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
                console.log(className);
                var methodName = $(this).find(':selected').text();
                $(Scheduler.edit.command).val(className + '::' + methodName);
            });
        },
    }
}