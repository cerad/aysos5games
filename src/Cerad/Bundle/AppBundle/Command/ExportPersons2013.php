<?php
namespace Cerad\Bundle\AppBundle\Command;

class ExportPersons2013
{
    protected $conn;
    protected $items;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    /* =================================================================
     * Accounts
     */
    protected function processUsers($personGuid)
    {
        $sql = <<<EOT
SELECT
    user.person_guid      AS personGuid,
    user.person_status    AS personStatus,
    user.person_verified  AS personVerified,
    user.person_confirmed AS personConfirmed,
                
    user.id                 AS userId,
    user.username           AS username,
    user.username_canonical AS usernameCanonical,
    user.email              AS email,
    user.email_canonical    AS emailCanonical,
    user.email_confirmed    AS emailConfirmed,
                
    user.salt               AS salt,
    user.password           AS password,
    user.password_hint      AS passwordHint,
    user.roles              AS roles,
                
    user.account_name          AS accountName,
    user.account_created_on    AS accountCreatedOn,
    user.account_updated_on    AS accountUpdatedOn,
    user.account_last_login_on AS accountLastLoginOn

FROM  users AS user
WHERE person_guid = :personGuid
EOT;
        $rows = $this->conn->fetchAll($sql,array('personGuid' => $personGuid));
        
        foreach($rows as &$row)
        {
            $row['roles'] = unserialize($row['roles']);
        }
        return $rows;
    }
    /* =================================================================
     * Certs
     */
    protected function processFedCerts($personId,$cvpa)
    {
        $sql = <<<EOT
SELECT
    cert.role           AS role,
    cert.date_cert      AS roleDate,
    cert.badge          AS badge,
    cert.badgex         AS badgeUser,
    cert.date_upgraded  AS badgeDate,
    cert.upgrading      AS upgrading,
    cert.status         AS status
                
FROM  person_cert AS cert
WHERE person_id = :personId 
ORDER BY role
EOT;
        $rows = $this->conn->fetchAll($sql,array('personId' => $personId));
        
        // Hack to add safe haven cert
        if (!$cvpa || $cvpa == 'None') return $rows;
        
        $rows[] = array(
            'role' => 'SafeHaven',
            'badge' => $cvpa,
        );
        return $rows;
    }
    /* =======================================================
     * Feds collection
     * For 2013 this is the person_league table
     * Verified that each person has one and only one person league
     */
    protected function processFeds($personId)
    {   
        $sql = <<<EOT
SELECT 
    fed.fed        AS fed,
    fed.identifier AS fedKey,
    fed.league     AS orgKey,
    fed.mem_year   AS memYear,
    fed.status     AS status,
    fed.cvpa       AS cvpa
                
FROM  person_league AS fed
WHERE fed.person_id = :personId
ORDER BY  fed.id
EOT;
        $sql .= ";\n";
        
        $rows = $this->conn->fetchAll($sql,array('personId' => $personId));
        foreach($rows as &$row)
        {
            $row['fedRole'] = 'AYSOV';
            
            if ($row['memYear'] == 'None') $row['memYear'] = null;
            if ($row['memYear'])
            {
                $row['memYear'] = 'MY' . $row['memYear'];
            }
            $row['certs'] = $this->processFedCerts($personId,$row['cvpa']);
            unset($row['cvpa']);
        }
        return $rows;
    }
    /* =======================================================
     * Persons collection
     */
    protected function processPersons()
    {   
        $sql = <<<EOT
SELECT 
    person.id          AS guid,
    person.name        AS nameFull,
    person.first_name  AS nameFirst,
    person.last_name   AS nameLast,
    person.nick_name   AS nameNick,
    person.email       AS email,
    person.phone       AS phone,
    person.gender      AS gender,
    person.dob         AS dob,
    person.city        AS addressCity,
    person.state       AS addressState,
    person.status      AS status,
    person.verified    AS verified
FROM      person AS person
ORDER BY  person.id
EOT;
      //$sql .= "\nLIMIT 0,3";
        $sql .= ";\n";
        
        $rows = $this->conn->fetchAll($sql);
        
        foreach($rows as &$row)
        {   
            // F70E6F1B-5D7E-4DE8-B627-DB01968454C2
            $guid = $row['guid'];
            $row['guid'] = sprintf('%s-%s-%s-%s-%s',
                substr($guid, 0, 8),
                substr($guid, 8, 4),
                substr($guid,12, 4),
                substr($guid,16, 4),
                substr($guid,20,12)
            );
            $row['feds' ] = $this->processFeds($guid);
          //$row['users'] = $this->processUsers($row['guid']);
        }
        return $rows;
    }
    /* ==========================================================================
     * Main entry point
     */
    public function process()
    {   
        return $this->processPersons();
    }
}
?>
