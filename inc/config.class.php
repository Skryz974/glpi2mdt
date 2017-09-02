<?php
/*
 -------------------------------------------------------------------------
 glpi2mdt plugin for GLPI
 Copyright (C) 2017 by Blaise Thauvin

 https://github.com/DebugBill/glpi2mdt
 -------------------------------------------------------------------------

 LICENSE

 This file is part of glpi2mdt.

 glpi2mdt is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 3 of the License, or
 (at your option) any later version.

 glpi2mdt is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with glpi2mdt. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
*/

// ----------------------------------------------------------------------
// Original Author of file: Blaise Thauvin
// Purpose of file: Plugin general settings management
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginGlpi2mdtConfig extends CommonDBTM {

   // Load plugin settings
   function loadConf() {
      global $DB;
      global $dbserver;
      global $dbport;
      global $dblogin;
      global $dbpassword;
      global $dbschema;
      global $mode;
      global $fileshare;

      $dbserver='';
      $dbport=3306;
      $dblogin='';
      $dbpassowrd='';
      $dbschema='';
      $mode='Strict';

      $query = "SELECT `parameter`, `value_char`, `value_num`
                FROM `glpi_plugin_glpi2mdt_parameters`
                WHERE `is_deleted` = 0 AND `scope`= 'global'";
      $result=$DB->query($query) or die("Error loading parameters from GLPI database ". $DB->error());

      while ($data=$DB->fetch_array($result)) {
         if ($data['parameter'] == 'DBServer') {
            $dbserver=$data['value_char']; }
         if ($data['parameter'] == 'DBPort') {
            $dbport=$data['value_num']; }
         if ($data['parameter'] == 'DBLogin') {
            $dblogin=$data['value_char']; }
         if ($data['parameter'] == 'DBPassword') {
            $dbpassword=$data['value_char']; }
         if ($data['parameter'] == 'DBSchema') {
            $dbschema=$data['value_char']; }
         if ($data['parameter'] == 'Mode') {
            $mode=$data['value_char']; }
         if ($data['parameter'] == 'Fileshare') {
            $fileshare=$data['value_char']; }
      }
   }

   // Store global plugin settings
   function updateValue($key, $value) {
      // Store configuration parameters
      global $DB;
      $validkeys=array(
                 'DBServer' => 'txt',
                 'DBLogin' => 'txt', 
                 'DBPassword' => 'txt',
                 'DBSchema' => 'txt',
                 'Mode' => 'txt',
                 'Fileshare' => 'txt',
                 'LocalAdmin' => 'txt',
                 'Complexity' => 'txt',
                 'DBPort' => 'num'
                );
      if ($validkeys[$key] == 'txt') {
         $query = "INSERT INTO `glpi_plugin_glpi2mdt_parameters`
                          (`parameter`, `scope`, `value_char`, `is_deleted`)
                          VALUES ('$key', 'global', '$value', false)
                   ON DUPLICATE KEY UPDATE value_char='$value', value_num=NULL, is_deleted=false";
         $DB->query($query) or die("Database error: ". $DB->error());
      }
      if ($validkeys[$key] == 'num' and $value > 0) {
         $query = "INSERT INTO `glpi_plugin_glpi2mdt_parameters`
                          (`parameter`, `scope`, `value_num`, `is_deleted`)
                          VALUES ('$key', 'global', '$value', false)
                   ON DUPLICATE KEY UPDATE value_num='$value', value_char=NULL, is_deleted=false";
         $DB->query($query) or die("Database error: ". $DB->error());
      }
   }

   function show() {
      global $DB;
      global $dbserver;
      global $dbport;
      global $dblogin;
      global $dbpassword;
      global $dbschema;
      global $mode;
      global $fileshare;

         ?>
           <form action="../front/config.form.php" method="post">
            <?php echo Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken())); ?>
            <div class="spaced" id="tabsbody">
                <table class="tab_cadre_fixe">
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Database server name'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="text" name="DBServer" value="'.$dbserver.'" size="50" class="ui-autocomplete-input" 
                                           autocomplete="off" required pattern="[a-Z0-9.]"> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Database server port (optionnal)'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="text" name="DBPort" value="'.$dbport.'" size="50" class="ui-autocomplete-input" 
                                           autocomplete="off" inputmode="numeric"> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Login'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="text" name="DBLogin" value="'.$dblogin.'" size="50" class="ui-autocomplete-input" 
                                           autocomplete="off" required pattern="[a-Z0-9]"> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Password'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="password" name="DBPassword" value="'.$dbpassword.'" size="50" class="ui-autocomplete-input" 
                                           autocomplete="off" required> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Schema'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="text" name="DBSchema" value="'.$dbschema.'" size="50" class="ui-autocomplete-input" 
                                           autocomplete="off" required pattern="[a-Z0-9]"> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Local path to deployment share control directory'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="text" name="Fileshare" value="'.$fileshare.'" size="50" class="ui-autocomplete-input" 
                                          autocomplete="off" required pattern="[a-Z0-9./\\$]"> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <td>
                              <?php echo _('Local admin password'); ?> : &nbsp;&nbsp;&nbsp;
                        </td><td>
                              <?php echo '<input type="text" name="LocalAdmin" value="'.$localadmin.'" size="50" class="ui-autocomplete-input" 
                                          autocomplete="off" required> &nbsp;&nbsp;&nbsp;' ?>
                        </td>
                    </tr>
                    <tr class="tab_bg_1">
                        <?php
                          echo "<td>"._('Local admin password complexity')." :</td>";
                          echo "<td>";
                          Dropdown::showFromArray("Complexity", array(
                             'Basic' => _("Same password on all machines"),
                             'Trivial' => _("Password is hostname"),
                             'Unique' => _("append '-%hostname%' to password"),
                             'value' => "$Complexity")
                          );
                          echo "</td>";
                           ?>
                          </tr>                    
                    <tr class="tab_bg_1">
                        <?php
                          echo "<td>"._('Link mode')." :</td>";
                          echo "<td>";
                          Dropdown::showFromArray("Mode", array(
                             'Strict' => _("Strict Master-Slave"),
                             'Loose' => _("Loose Master-Slave"),
                             'Master' => _("Master-Master")), array(
                             'value' => "$Mode")
                          );
                          echo "</td>";
                           ?>
                          </tr>
                          <tr class="tab_bg_1">
                           <td>
                            <input type="submit" class="submit" value=<<?php _("Save") ?> name="SAVE"/>
                           </td>
                          </tr>
                          <tr class="tab_bg_1">
                           <td>
                            <input type="submit" class="submit" value=<<?php _("Test connection") ?> name="TEST"/>
                           </td><td>
                            <input type="submit" class="submit" value=<<?php _("Initialise data") ?> name="INIT"/>
                           </td>
                          </tr>
                      </table>
                  </div>
              </form>
               <?php
               return true;
   }

   // Test connection
   function showTestConnection() {
      global $dbserver;
      global $dbport;
      global $dblogin;
      global $dbpassword;
      global $dbschema;
      ?>
      <table class="tab_cadre_fixe">
      <tr class="tab_bg_1">
         <td>
            <?php
            // Connection to MSSQL
            $link = mssql_connect($dbserver, $dblogin, $dbpassword);
            if ($link) { 
               echo "<h1><font color='green'> "._("Database login OK!")."</font></h1><br>";
               // Simple query to get database version
               $version = mssql_query('SELECT @@VERSION');
               $row = mssql_fetch_array($version);
               echo "Server is: <br>".$row[0]."<br>";
               if (mssql_select_db($dbschema, $link))
                  echo "<h1><font color='green'>"._("Schema selection OK!")."</font></h1><br>";
               else
                  echo "<h1><font color='red'>"._("Schema selection KO!")."</font></h1><br>";
            }
            else 
               echo "<h1><font color='red'>"._("Database login KO!")."</font></h1><br>";

            // Cleaning
            mssql_free_result($version);
            mssql_close($link);
            ?> 
         </td>
      </tr>
      </table>
      <?php
   }


   // Initialise data, load local tables from MDT MSSQL server
   function showInitialise() {
      global $DB;
      global $dbserver;
      global $dbport;
      global $dblogin;
      global $dbpassword;
      global $dbschema;
      global $fileshare;

      echo '<table class="tab_cadre_fixe">';
      // Connexion à MSSQL
      $link = mssql_connect($dbserver, $dblogin, $dbpassword);

      if (!$link || !mssql_select_db($dbschema, $link)) 
          die('Cannot connect to MDT MSSQL database!');

      //
      // Load available settings fields and descriptions from MDT
      //
      $result = mssql_query('SELECT  ColumnName, CategoryOrder, Category, Description
                              FROM dbo.Descriptions');
      $nb = mssql_num_rows($result);

      // Mark lines in order to detect deleted ones in the source database
      $DB->query("UPDATE `glpi_plugin_glpi2mdt_descriptions` SET is_in_sync=false WHERE is_deleted=false");
      // There less than 300 lines, do an atomic insert/update
      while ($row = mssql_fetch_array($result)) {
         $column_name = $row['ColumnName'];
         $category_order = $row['CategoryOrder'];
         $category = $row['Category'];
         $description = $row['Description'];

         $query = "INSERT INTO glpi_plugin_glpi2mdt_descriptions
                    (`column_name`, `category_order`, `category`, `description`, `is_in_sync`, `is_deleted`)
                    VALUES ('$column_name', $category_order, '$category', '$description', true, false)
                  ON DUPLICATE KEY UPDATE category_order=$category_order, category='$category', description='$description', is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT descriptions to GLPI database. ". $DB->error());
      }
      echo "<tr class='tab_bg_1'><td>$nb "."lines loaded into table 'descriptions'.".'</td>';
      $result = $DB->query("SELECT count(*) as nb FROM `glpi_plugin_glpi2mdt_descriptions` WHERE `is_in_sync`=false");
      $row = $DB->fetch_array($result);
      $nb = $row['nb'];
      $DB->query("UPDATE glpi_plugin_glpi2mdt_descriptions SET is_in_sync=true, is_deleted=true 
                      WHERE is_in_sync=false AND is_deleted=false");
      echo "<td>$nb "."lines deleted from table 'descriptions'.".'</td></tr>';

      //
      // Load available roles from MDT
      //
      $result = mssql_query('SELECT  ID, Role FROM dbo.RoleIdentity');

      // Mark lines in order to detect deleted ones in the source database
      $DB->query("UPDATE `glpi_plugin_glpi2mdt_roles` SET is_in_sync=false WHERE is_deleted=false");
      while ($row = mssql_fetch_array($result)) {
         $id = $row['ID'];
         $role = $row['Role'];

         $query = "INSERT INTO glpi_plugin_glpi2mdt_roles
                    (`id`, `role`, `is_deleted`, `is_in_sync`)
                    VALUES ('$id', '$role', false, true)
                  ON DUPLICATE KEY UPDATE role='$role', is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT roles to GLPI database. ". $DB->error());
      }

      // Mark lines which are not in MDT anymore as deleted
      $DB->query("UPDATE glpi_plugin_glpi2mdt_roles SET is_in_sync=true, is_deleted=true 
                    WHERE is_in_sync=false AND is_deleted=false");

      $result = mssql_query('SELECT  count(*) as nb FROM dbo.RoleIdentity');
      $row = mssql_fetch_array($result);
      $nb = $row['nb'];
      echo "<tr class='tab_bg_1'><td>$nb "."lines loaded into table 'roles'.".'</td></tr>';


      // Cleaning
      mssql_free_result($result);
      mssql_close($link);

      //
      // Load data from XML files in the deployment share 
      //
      // Applications
      // Mark lines in order to detect deleted ones in the source database
      $DB->query("UPDATE glpi_plugin_glpi2mdt_applications SET is_in_sync=false WHERE is_deleted=false");
      $applications = simplexml_load_file($fileshare.'/Applications.xml') 
              or die("Cannot load file $fileshare/Applications.xml");
      $nb = 0;
      foreach ($applications->application as $application) {
         $name = $application->Name;
         $guid = $application['guid'];
         if (isset($application['enable']) and ($application['enable'] == 'True')) $enable = 'true'; else $enable = 'false';
         if (isset($application['hide']) and ($application['hide'] == 'True')) $hide = 'true'; else $hide = 'false';
         $shortname = $application->ShortName;
         $version = $application->Version;

         $query = "INSERT INTO glpi_plugin_glpi2mdt_applications
                    (`guid`, `name`, `shortname`, `version`, `hide`, `enable`, `is_deleted`, `is_in_sync`)
                    VALUES ('$guid', '$name', '$shortname', '$version', $hide, $enable, false, true)
                  ON DUPLICATE KEY UPDATE name='$name', shortname='$shortname', version='$version', hide=$hide, 
                                          enable=$enable, is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT applications to GLPI database. ". $DB->error());
         $nb += 1;
      }
      echo "<tr class='tab_bg_1'><td>$nb "._("lines loaded into table")." 'applications'.</td>";
      // Mark lines which are not in MDT anymore as deleted
      $result = $DB->query("SELECT count(*) as nb FROM glpi_plugin_glpi2mdt_applications WHERE `is_in_sync`=false");
      $row = $DB->fetch_array($result);
      $nb = $row['nb'];
      $DB->query("UPDATE glpi_plugin_glpi2mdt_applications SET is_in_sync=true, is_deleted=true 
                      WHERE is_in_sync=false AND is_deleted=false");
      echo "<td>$nb "._("lines deleted from table")."'applications' </td><tr>";

      // Application groups
      // Mark lines in order to detect deleted ones in the source database
      $DB->query("UPDATE glpi_plugin_glpi2mdt_application_groups SET is_in_sync=false WHERE is_deleted=false");
      $DB->query("UPDATE glpi_plugin_glpi2mdt_application_group_link SET is_in_sync=false WHERE is_deleted=false");
      $groups = simplexml_load_file($fileshare.'/ApplicationGroups.xml')
              or die("Cannot load file $fileshare/ApplicationGroups.xml");
      $nb = 0;
      foreach ($groups->group as $group) {
         $name = $group->Name;
         $guid = $group['guid'];
         if (isset($group['enable']) and ($group['enable'] == 'True')) $enable = 'true'; else $enable = 'false';
         if (isset($group['hide']) and ($group['hide'] == 'True') and ($name <> 'hidden')) $hide = 'true'; else $hide = 'false';

         $query = "INSERT INTO glpi_plugin_glpi2mdt_application_groups
                    (`guid`, `name`, `hide`, `enable`, `is_deleted`, `is_in_sync`)
                    VALUES ('$guid', '$name', $hide, $enable, false, true)
                  ON DUPLICATE KEY UPDATE name='$name', hide=$hide, enable=$enable, is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT application groups to GLPI database. ". $DB->error());
         $nb += 1;
         foreach ($group->member as $application_guid) {
            $query = "INSERT INTO glpi_plugin_glpi2mdt_application_group_links
                    (`group_guid`, `application_guid`, `is_deleted`, `is_in_sync`)
                    VALUES ('$guid', '$application_guid', false, true)
                  ON DUPLICATE KEY UPDATE is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT application-group links to GLPI database. ". $DB->error());
         }
      }
      echo "<tr class='tab_bg_1'><td>$nb "._("lines loaded into table")." 'application groups'.</td>";
      // Mark lines which are not in MDT anymore as deleted
      $result = $DB->query("SELECT count(*) as nb FROM glpi_plugin_glpi2mdt_application_groups WHERE `is_in_sync`=false");
      $row = $DB->fetch_array($result);
      $nb = $row['nb'];
      $DB->query("UPDATE glpi_plugin_glpi2mdt_application_groups SET is_in_sync=true, is_deleted=true 
                      WHERE is_in_sync=false AND is_deleted=false");
      $DB->query("DELETE FROM glpi_plugin_glpi2mdt_application_group_links 
                      WHERE is_in_sync=false AND is_deleted=false");
      echo "<td>$nb "._("lines deleted from table")." 'application_group_links'.</td></tr>";

      // Task sequences
      // Mark lines in order to detect deleted ones in the source database
      $DB->query("UPDATE glpi_plugin_glpi2mdt_task_sequences SET is_in_sync=false WHERE is_deleted=false");
      $tss = simplexml_load_file($fileshare.'/TaskSequences.xml')
              or die("Cannot load file $fileshare/TaskSequences.xml");
      $nb = 0;
      foreach ($tss->ts as $ts) {
         $name = $ts->Name;
         $guid = $ts['guid'];
         $id = $ts->ID;
         if (isset($ts['enable']) and ($ts['enable'] == 'True')) $enable = 'true'; else $enable = 'false';
         if (isset($ts['hide']) and ($ts['hide'] == 'True')) $hide = 'true'; else $hide = 'false';

         $query = "INSERT INTO glpi_plugin_glpi2mdt_task_sequences
                    (`id`, `guid`, `name`, `hide`, `enable`, `is_deleted`, `is_in_sync`)
                    VALUES ('$id', '$guid', '$name', $hide, $enable, false, true)
                  ON DUPLICATE KEY UPDATE guid='$guid', name='$name', hide=$hide, enable=$enable, is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT task sequences into GLPI database. ". $DB->error());
         $nb += 1;
      }
      echo "<tr class='tab_bg_1'><td>$nb "._("lines loaded into table")." 'task_sequences'.</td>";
      // Mark lines which are not in MDT anymore as deleted
      $result = $DB->query("SELECT count(*) as nb FROM glpi_plugin_glpi2mdt_task_sequences WHERE `is_in_sync`=false");
      $row = $DB->fetch_array($result);
      $nb = $row['nb'];
      $DB->query("UPDATE glpi_plugin_glpi2mdt_task_sequences SET is_in_sync=true, is_deleted=true 
                      WHERE is_in_sync=false AND is_deleted=false");
      echo "<td>$nb "._("lines deleted from table")." 'task_sequence'.</td></tr>";

      // Task sequence groups
      // Mark lines in order to detect deleted ones in the source database
      $DB->query("UPDATE glpi_plugin_glpi2mdt_task_sequence_groups SET is_in_sync=false WHERE is_deleted=false");
      $DB->query("UPDATE glpi_plugin_glpi2mdt_task_sequence_group_link SET is_in_sync=false WHERE is_deleted=false");
      $groups = simplexml_load_file($fileshare.'/TaskSequenceGroups.xml')
              or die("Cannot load file $fileshare/TaskSequenceGroups.xml");
      $nb = 0;
      foreach ($groups->group as $group) {
         $name = $group->Name;
         $guid = $group['guid'];
         if (isset($group['enable']) and ($group['enable'] == 'True')) $enable = 'true'; else $enable = 'false';
         if (isset($group['hide']) and ($group['hide'] == 'True') and ($name <> 'hidden')) $hide = 'true'; else $hide = 'false';

         $query = "INSERT INTO glpi_plugin_glpi2mdt_task_sequence_groups
                    (`guid`, `name`, `hide`, `enable`, `is_deleted`, `is_in_sync`)
                    VALUES ('$guid', '$name', $hide, $enable, false, true)
                  ON DUPLICATE KEY UPDATE name='$name', hide=$hide, enable=$enable, is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT task sequence groups to GLPI database. ". $DB->error());
         $nb += 1;
         foreach ($group->member as $sequence_guid) {
            $query = "INSERT INTO glpi_plugin_glpi2mdt_application_group_links
                    (`group_guid`, ``sequence_guid`, `is_deleted`, `is_in_sync`)
                    VALUES ('$guid', '$sequence_guid', false, true)
                  ON DUPLICATE KEY UPDATE is_deleted=false, is_in_sync=true";
         $DB->query($query) or die("Error loading MDT sequence-group links to GLPI database. ". $DB->error());
         }
      }
      echo "<tr class='tab_bg_1'><td>$nb "._("lines loaded into table")." 'task sequence groups'.</td>";
      // Mark lines which are not in MDT anymore as deleted
      $result = $DB->query("SELECT count(*) as nb FROM glpi_plugin_glpi2mdt_task_sequence_groups WHERE `is_in_sync`=false");
      $row = $DB->fetch_array($result);
      $nb = $row['nb'];
      $DB->query("UPDATE glpi_plugin_glpi2mdt_task_sequence_groups SET is_in_sync=true, is_deleted=true 
                      WHERE is_in_sync=false AND is_deleted=false");
      $DB->query("DELETE FROM glpi_plugin_glpi2mdt_task_sequence_group_links 
                      WHERE is_in_sync=false AND is_deleted=false");
      echo "<td>$nb "._("lines deleted from table")." 'task_sequence_group_links'.</td></tr></table>";
   }

}