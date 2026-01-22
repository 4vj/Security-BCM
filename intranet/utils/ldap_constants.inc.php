<?php
// set some constants
/**
 * The basis where our data is located in the Directory Information Tree (DIT) in de slapd store
 */
define("BASE_DN","dc=ldap,dc=energy,dc=local");

define("GROUP_PATH", "ou=groups," . BASE_DN);

/**
 * The DN (relative to the BASE_DN) where the group for all website users is located
 */
define("GROUPS_DN",            "cn=allusers,ou=groups," . BASE_DN );

/**
 * The DN (relative to the BASE_DN) of the OrganisationalUnit (OU) of the external users
 */
define("USERS_EXTERN_DN",      "ou=extern,"                         . BASE_DN );

/**
 * The DN (relative to the BASE_DN) of the OrganisationalUnit (OU) of the internal users
 */
define("USERS_INTERN_DN",      "ou=users,ou=energy,"                         . BASE_DN );

/**
 * The DN (relative to the BASE_DN) of the OrganisationalUnit (OU) of the application users
 */
define("USERS_APPLICATION_DN", "ou=users,ou=energy,"                    . BASE_DN );

/**
 * The LDAP attribute name that is used to store one user's DN in a GroupOfUniqueNames-object.
 */
define("GROUP_ATTR_NAME", "uniqueMember");

/**
 * The IP-address of our LDAP host/server; In this case LOCALHOST is used (on the same server as this website)
 */
define("LDAP_HOST","127.0.0.1");

/**
 * The port used to connect to the server (the value 389 is already the default value)
 */
define("LDAP_PORT",389);

// FIXME: This is GOD. should be cn=webuserldap,ou=application,o=Energy,dc=energy,dc=org
/**
 * The user that is used to connect to the LDAP-store to query and make changes.
 * <strong>Problem: this user has too many rights and cn=webuserldap,.... has not enough.</strong>
 */
define("LDAP_ADMIN_CN","cn=admin,dc=ldap, dc=energy, dc=local");

// FIXME: Investigate how to prevent plaintext passwords.
/**
 * Plaintext password. <strong>This should be prevented!</strong>
 */
define("LDAP_PASSWORD","%^&asdfghjkl*()");
