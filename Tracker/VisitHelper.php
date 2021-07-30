<?php

namespace Tracker;

/**
 * Class VisitHelper
 *
 * Helper functions for working with Visitor records
 */
class VisitHelper {
    const ID = 'id';
    const USER_ID = 'user_id';
    const TITLE = 'title';
    const VISIT_DATE = 'visit_date';
    const SITE = 'site';
    const USER_IP = 'user_ip';
    const USER_AGENT = 'user_agent';
    const COUNTRY = 'country';
    const STATE = 'state';
    const CITY = 'city';

    public static function addVisit(
        $user_id,
        $title,
        $visit_date,
        $site,
        $user_ip,
        $user_agent,
        $country,
        $state,
        $city
    ) {
        if(self::_checkIfDomainIsBanned($site)){
            return;
        }

        $connection = DbConnector::getNewConnection();
        $queryString = 'INSERT INTO visits (' . self::_getQueryFieldNames() . ') VALUES('. self::_getQueryValueNames().')';
        $statement = $connection->prepare($queryString);

        $values = array_combine(self::getFields(), [
            $user_id, $title, $visit_date, $site, $user_ip, $user_agent, $country, $state, $city
        ]);

        self::_bindValuesToStatement($statement, $values);

        $statement->execute();
    }

    protected static function _bindValuesToStatement(PDOStatement $statement, $values) {
        foreach($values as $fieldName => $fieldValue) {
            $statement->bindValue(':'.$fieldName, $fieldValue);
        }

        return $statement;
    }

    public static function getFields() {
        return [
            self::USER_ID,
            self::TITLE,
            self::VISIT_DATE,
            self::SITE,
            self::USER_IP,
            self::USER_AGENT,
            self::COUNTRY,
            self::STATE,
            self::CITY
        ];
    }

    protected static function _getQueryFieldNames() {
        return implode(',', self::getFields());
    }

    protected static function _getQueryValueNames() {
        return ':' . implode(',:', self::getFields());
    }

    protected static function _checkIfDomainIsBanned($domain) {
        $settings = Settings::getSettings();

        if(!isset($settings['BANNED_DOMAINS'])) {
            return false;
        }

        $bannedDomains = explode(',', $settings['BANNED_DOMAINS']);

        if(in_array($domain, $bannedDomains)) {
            return true;
        }

        return false;
    }

    public static function getAllVisits() {}
}
