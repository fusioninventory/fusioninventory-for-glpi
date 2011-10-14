<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: mapping table fields with constants
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

global $LANG, $FUSIONINVENTORY_MAPPING_FIELDS;

$FUSIONINVENTORY_MAPPING_FIELDS['name']                 = $LANG['common'][16];
$FUSIONINVENTORY_MAPPING_FIELDS['serial']               = $LANG['common'][19];
$FUSIONINVENTORY_MAPPING_FIELDS['otherserial']          = $LANG['common'][20];
$FUSIONINVENTORY_MAPPING_FIELDS['contact']              = $LANG['common'][18];
$FUSIONINVENTORY_MAPPING_FIELDS['contact_num']          = $LANG['common'][21];
$FUSIONINVENTORY_MAPPING_FIELDS['tech_num']             = $LANG['common'][10];
$FUSIONINVENTORY_MAPPING_FIELDS['comments']             = $LANG['common'][25];
$FUSIONINVENTORY_MAPPING_FIELDS['os']                   = $LANG['computers'][9];
$FUSIONINVENTORY_MAPPING_FIELDS['os_version']           = $LANG['computers'][52];
$FUSIONINVENTORY_MAPPING_FIELDS['os_sp']                = $LANG['computers'][53];
$FUSIONINVENTORY_MAPPING_FIELDS['os_license_number']    = $LANG['computers'][10];
$FUSIONINVENTORY_MAPPING_FIELDS['os_license_id']        = $LANG['computers'][11];
$FUSIONINVENTORY_MAPPING_FIELDS['location']             = $LANG['common'][15];
$FUSIONINVENTORY_MAPPING_FIELDS['domain']               = $LANG['setup'][89];
$FUSIONINVENTORY_MAPPING_FIELDS['network']              = $LANG['setup'][88];
$FUSIONINVENTORY_MAPPING_FIELDS['model']                = $LANG['common'][22];
$FUSIONINVENTORY_MAPPING_FIELDS['FK_glpi_enterprise']   = $LANG['common'][5];
$FUSIONINVENTORY_MAPPING_FIELDS['notes']                = $LANG['title'][37];
$FUSIONINVENTORY_MAPPING_FIELDS['FK_users']             = $LANG['common'][34];
$FUSIONINVENTORY_MAPPING_FIELDS['FK_groups']            = $LANG['common'][35];
$FUSIONINVENTORY_MAPPING_FIELDS['state']                = $LANG['state'][0];
$FUSIONINVENTORY_MAPPING_FIELDS['ram']                  = $LANG['networking'][5];
$FUSIONINVENTORY_MAPPING_FIELDS['firmware']             = $LANG['setup'][71];
$FUSIONINVENTORY_MAPPING_FIELDS['ifmac']                = $LANG['networking'][15];
$FUSIONINVENTORY_MAPPING_FIELDS['ifaddr']               = $LANG['networking'][14];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_serial']         = $LANG['printers'][18]." : ".$LANG['printers'][14];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_par']            = $LANG['printers'][18]." : ".$LANG['printers'][15];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_usb']            = $LANG['printers'][18]." : ".$LANG['printers'][27];
$FUSIONINVENTORY_MAPPING_FIELDS['ramSize']              = $LANG['devices'][6];
$FUSIONINVENTORY_MAPPING_FIELDS['is_global']            = $LANG['peripherals'][33];
$FUSIONINVENTORY_MAPPING_FIELDS['initial_pages']        = $LANG['printers'][30];
$FUSIONINVENTORY_MAPPING_FIELDS['logical_number']       = $LANG['networking'][21];
$FUSIONINVENTORY_MAPPING_FIELDS['iface']                = $LANG['common'][65];
$FUSIONINVENTORY_MAPPING_FIELDS['netpoint']             = $LANG['networking'][51];
$FUSIONINVENTORY_MAPPING_FIELDS['netmask']              = $LANG['networking'][60];
$FUSIONINVENTORY_MAPPING_FIELDS['gateway']              = $LANG['networking'][59];
$FUSIONINVENTORY_MAPPING_FIELDS['subnet']               = $LANG['networking'][61];
$FUSIONINVENTORY_MAPPING_FIELDS['size']                 = $LANG['monitors'][21];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_micro']          = $LANG['monitors'][18]." : ".$LANG['monitors'][14];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_speaker']        = $LANG['monitors'][18]." : ".$LANG['monitors'][15];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_subd']           = $LANG['monitors'][18]." : ".$LANG['monitors'][19];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_bnc']            = $LANG['monitors'][18]." : ".$LANG['monitors'][20];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_dvi']            = $LANG['monitors'][18]." : ".$LANG['monitors'][32];
$FUSIONINVENTORY_MAPPING_FIELDS['flags_pivot']          = $LANG['monitors'][18]." : ".$LANG['monitors'][33];

$FUSIONINVENTORY_MAPPING_FIELDS['buy_date']             = $LANG['financial'][14];
$FUSIONINVENTORY_MAPPING_FIELDS['use_date']             = $LANG['financial'][76];
$FUSIONINVENTORY_MAPPING_FIELDS['warranty_duration']    = $LANG['financial'][15];
$FUSIONINVENTORY_MAPPING_FIELDS['warranty_info']        = $LANG['financial'][16];
$FUSIONINVENTORY_MAPPING_FIELDS['FK_enterprise']        = $LANG['financial'][26];
$FUSIONINVENTORY_MAPPING_FIELDS['num_commande']         = $LANG['financial'][18];
$FUSIONINVENTORY_MAPPING_FIELDS['bon_livraison']        = $LANG['financial'][19];
$FUSIONINVENTORY_MAPPING_FIELDS['num_immo']             = $LANG['financial'][20];
$FUSIONINVENTORY_MAPPING_FIELDS['value']                = $LANG['financial'][21];
$FUSIONINVENTORY_MAPPING_FIELDS['warranty_value']       = $LANG['financial'][78];
$FUSIONINVENTORY_MAPPING_FIELDS['amort_time']           = $LANG['financial'][23];
$FUSIONINVENTORY_MAPPING_FIELDS['amort_type']           = $LANG['financial'][22];
$FUSIONINVENTORY_MAPPING_FIELDS['amort_coeff']          = $LANG['financial'][77];
$FUSIONINVENTORY_MAPPING_FIELDS['facture']              = $LANG['financial'][82];
$FUSIONINVENTORY_MAPPING_FIELDS['budget']               = $LANG['financial'][87];

?>