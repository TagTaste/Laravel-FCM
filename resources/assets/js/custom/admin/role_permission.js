/**
 * validate the add/edit permission form
 */
$(function(){
    $("#addPermission, #editPermission").validate();
});

/**
 * delete permission
 */
$(".deletePermission").click(function() {
    var permission_id = this.value;
    swal({
        title: "Are you sure?",
        text: "You want to delete this permission!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#E53935",
        confirmButtonText: "Yes, Delete !",
        cancelButtonText: "No, Cancel !",
        closeOnConfirm: false,
        closeOnCancel: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url:"/admin/permission/destroy/"+permission_id,
                success : function(data) {
                    window.location = "/admin/permission/view";
                }
            });
        }
    });
});

/**
 * edit permission
 */
$(".editPermission").click(function() {
    window.location = "/admin/permission/edit/"+this.value;
});

/**
 * validate the add/edit role form
 */
$(function(){
    $("#addRole, #editRole").validate();
});

/**
 * delete role
 */
$(".deleteRole").click(function() {
    var role_id = this.value;
    swal({
        title: "Are you sure?",
        text: "You want to delete this role!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#E53935",
        confirmButtonText: "Yes, Delete !",
        cancelButtonText: "No, Cancel !",
        closeOnConfirm: false,
        closeOnCancel: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url:"/admin/role/destroy/"+role_id,
                success : function(data) {
                    window.location = "/admin/role/view";
                }
            });
        }
    });
});

/**
 * edit role
 */
$(".editRole").click(function() {
    window.location = "/admin/role/edit/"+this.value;
});