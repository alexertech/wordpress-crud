<?php
function alex_crud_update () {
  global $wpdb;

  // Get Values
  $id    = sanitize_key($_GET['id']);
  $name  = sanitize_text_field($_POST['name']);
  $email = sanitize_email($_POST['email']);
  $msg   = "";

  //update
  if(isset($_POST['update'])){

    // Validations
    if (!preg_match("/^[0-9]*$/",$id) || empty($id)) {
      $msg = "error:Only numbers allowed in the ID";
    } elseif (!preg_match("/^[a-zA-Z ]*$/",$name) or empty($name)) {
      $msg = "error:Only letters and white space allowed in the name";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $msg = "error:Invalid email format";
    } else {

    	$wpdb->update(
    		'contacts',                                    //table
    		array('name' => $name, 'email' => $email),     //data
    		array('ID' => $id ),                           //where
    		array('%s'),                                   //data format
    		array('%s')                                    //where format
    	);

      $msg = "updated:Contact updated!";

    }
  }

  // selecting value to update
  $contacts = $wpdb->get_row(
    $wpdb->prepare("SELECT id,name,email from contacts where id=%d",$id)
  );

  ?>
  <link href="<?php echo WP_PLUGIN_URL; ?>/alex_crud/alex_crud_style.css"
        type="text/css" rel="stylesheet" />
  <div class="wrap">
    <h2>Update Contacts</h2>

    <?php
    if (!empty($msg)) {
      $fmsg = explode(':',$msg);
      echo "<div class=\"{$fmsg[0]}\"><p>{$fmsg[1]}</p></div>";
    }
    ?>

    <p>
      <a href="<?php echo admin_url('admin.php?page=alex_crud_list')?>">
        &laquo; Back to contacts list</a>
    </p>

    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
      <table class='wp-list-table widefat fixed'>
      <tr>
        <th>Name</th>
        <td><input type="text" name="name" value="<?php echo $contacts->name;?>"/></td>
      </tr>
      <tr>
        <th>Email</th>
        <td><input type="text" name="email" value="<?php echo $contacts->email;?>"/></td>
      </tr>
      </table>
      <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
    </form>

  </div>
  <?php
}
