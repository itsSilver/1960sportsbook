<?php

class User extends AppModel {

    public $name = 'User';
    public $actsAs = array('Containable');
    public $belongsTo = array('Group', 'Language');
    
	public $hasMany = array('Ticket', 'BonusCodesUser', 'Deposit', 'Withdraw','PaymentBonusUsage','JackpotWinning',
    		'SignedUp' => array('className' => 'Referral', 'foreignKey' => 'user_id'),
    		'ReferredBy' => array('className' => 'Referral', 'foreignKey' => 'referral_id'));   
    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'allowEmpty' => false,
                'message' => 'Alphabets and numbers only'
            ),
            'between' => array(
                'rule' => array('between', 5, 15),
                'message' => 'Between 5 to 15 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            )
        ),
        'password_raw' => array(
            'rule' => array('minLength', '2'),
            'message' => 'Mimimum 2 characters long'
        ),
        'password_confirm' => array(
            'rule' => array('minLength', '2'),
            'message' => 'Mimimum 2 characters long'
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email has already been registered.'
            )
        ),
        'first_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your name'
        ),
        'last_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your surname'
        ),
        'address1' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter your street address'
        ),
        'city' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter city'
        ),
        'country' => array(
            'rule' => array('minLength', '1'),
            'allowEmpty' => false,
            'message' => 'Please enter country'
        ),
        'date_of_birth' => array(
            'rule' => 'date',
            'allowEmpty' => false,
            'message' => 'Please enter valid birth date'
        ),
        'mobile_number' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter valid mobile number'
        ),
        'personal_question' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please your personal question'
        ),
        'personal_answer' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter valid answer'
        ),
    	'bank_name' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter valid answer'
    	),
    	'account_number ' => array(
    		'rule' => array('minLength', '2'),
    		'allowEmpty' => false,
    		'message' => 'Please enter valid answer'
    	),
        'agree' => array(
            'rule' => array('inList', array('1', 1, 'true', true, 'on')),
            'message' => 'You need to accept the Terms Of Use to be able to register.'
        )
    );

	function userData($id) {
        $options['conditions'] = array(
            'User.id' => $id
        );
        return $this->find('all', $options);
    }

	function userGroup($username) {
        $options['conditions'] = array(
            'User.username' => $username
        );
        return $this->find('first', $options);
    }

	function dataUserGroup($id) {
        $options['conditions'] = array(
            'User.id' => $id,
			'User.group_id' => 1
        );
        return $this->find('first', $options);
    }

	function getUser($tableName,$fieldId,$fieldValue) {
		$sql ="Select * from `".$tableName."` where `".$fieldId."` = '".$fieldValue."' ";
		return $this->query($sql);
    }

	function allAgent($groupid) {
        $options['conditions'] = array(
            'User.group_id' => $groupid
        );
        return $this->find('all', $options);
    }

    function getActions() {
        $actions = array();			
		$actions[] = array('name' => __('Edit', true), 'action' => 'edit', 'controller' => NULL);
		$actions[] = array('name' => __('Delete', true), 'action' => 'delete', 'controller' => NULL);		
        $actions[] = array('name' => __('Add balance', true), 'action' => 'addBalance', 'controller' => NULL);
        $actions[] = array('name' => __('Deposit bonus history',true),'action' => 'deposit_bonus_history', 'controller' => NULL);
        $actions[] = array('name' => __('Report', true), 'action' => 'userReport', 'controller' => 'reports');
        return $actions;
    }

    function getIndex() {
        $options['fields'] = array(
            'User.id',
            'User.username',
            'User.email',
            'User.balance',
        );
        $options['conditions'] = array(
            'User.group_id' => 1
        );
        return $options;
    }

    function getView($id) {
        $options['fields'] = array(
            'User.id',
            'User.username',
            'User.email',
            'User.balance',
            'User.first_name',
            'User.last_name',
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.date_of_birth',
            'User.mobile_number',
            'User.last_visit',
            'User.bank_name',
            'User.account_number'
        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'User.id' => $id,
            'User.group_id' => 1
        );

        $data = $this->find('first', $options);
        return $data;
    }

    function getEdit() {
        $fields = array(
            'User.username',
            'User.password',
            'User.email',
            'User.first_name',
            'User.last_name',
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.date_of_birth',
            'User.mobile_number',
            'User.status' => array(
                'type' => 'select',
                'options' => array(
                    '0' => 'email not confirmed',
                    '1' => 'email confirmed',
                    '2' => 'suspended',
                )
            )
        );
        return $fields;
    }

    function getSearch() {
        $fields = array(
            'User.username',
            'User.email',
            'User.balance',
            'User.first_name',
            'User.last_name',
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.date_of_birth',
            'User.mobile_number'
        );
        return $fields;
    }

    function getadd() {
        $fields = array(
            'User.username',
            'User.password_raw' => array('type' => 'password', 'label' => __('Password')),
            'User.email',
            'User.first_name',
            'User.last_name',
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.date_of_birth',
            'User.mobile_number'
        );
        return $fields;
    }

    function updateLastVisit($id) {
        $user = $this->getItem($id);
        $user['User']['last_visit'] = $this->getSqlDate();
        $this->save($user, false);
    }

    function getTicketsActions() {
        $actions = array();
        $actions[] = array('name' => __('View', true), 'action' => 'view', 'controller' => NULL);
        $actions[] = array('name' => __('Cancel', true), 'action' => 'Cancel', 'controller' => 'tickets'); // 11/26/2012,cancele 
        return $actions;
    }

    //deposit function
    function addFunds($id, $amount) {
		
		$user = $this->getItem($id);
        $user['User']['balance'] += $amount;
        //TODO can we have <0 balance?
        if ($user['User']['balance'] < 0)
            $user['User']['balance'] = 0;
        $this->save($user, false);
    }

    function parentNode() {
        if (!$this->id && empty($this->request->data)) {
            return null;
        }
        $data = $this->request->data;
        if (empty($this->request->data)) {
            $data = $this->read();
        }
        if (!$data['User']['group_id']) {
            return null;
        } else {
            return array('Group' => array('id' => $data['User']['group_id']));
        }
    }

    function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }

    function getReport($from, $to, $userId = null, $limit = NULL) {
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'User.registration_date BETWEEN ? AND ?' => array($from, $to),
            'User.group_id' => 1
        );
        if ($userId != NULL)
            $options['conditions']['User.id'] = $userId;
        if ($limit != NULL)
            $options['limit'] = $limit;
        $data = $this->find('all', $options);
        $data['header'] = array(
            'User ID',
            'Date of registration',
            'Username',
            'Balance',
            'First name',
            'Last name',
            'Address first line',
            'Address second line',
            'Zip/Post code',
            'City',
            'Country',
            'Email',
            'Telephone number',
            'Date of birth'
        );
        return $data;
    }

    function getAllEmails() {
        $options['fields'] = array(
            'User.id',
            'User.email'
        );
        $options['conditions'] = array(
            'User.group_id' => 1
        );
        $emails = $this->find('list', $options);
        return $emails;
    }

    function getQuestions() {
        $questions = array('Favorite team?' => 'Favorite team?', 'Favorite food?' => 'Favorite food?', 'My dog name?' => 'My dog name?');
        return $questions;
    }

    function getCountriesList() {
        $countries = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan ',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain ',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad (Tchad)',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros (Comores)',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic of the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',           
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'KP' => 'North Korea',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territories',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',         
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'CS' => 'Serbia and Montenegro',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'KR' => 'South Korea',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia)',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States minor outlying islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );
        return $countries;
    }

    function getValidation() {
        return array(
            'username' => array(
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'allowEmpty' => false,
                    'message' => 'Alphabets and numbers only'
                ),
                'between' => array(
                    'rule' => array('between', 5, 15),
                    'message' => 'Between 5 to 15 characters'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'This username has already been taken.'
                )
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address'
            ),
            'password_raw' => array(
                'rule' => array('minLength', '2'),
                'message' => 'Mimimum 2 characters long'
            )
        );
    }

	function saveGlobalDataUser($table_name=null,$coloum_field=null, $coloum_value=null,$updated_on_field=null,$updated_on_value=null,$otherfields=null) {
		$sql ="UPDATE `".$table_name."` SET `".$coloum_field."` = '".$coloum_value."' where `".$updated_on_field."` = '".$updated_on_value."' ".$otherfields." ";
		$return = $this->query($sql);
		return true;
	}

}

?>
