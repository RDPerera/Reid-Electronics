<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class CachedCiviContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();

        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();
        $this->scopes = array();
        $this->scopeChildren = array();
        $this->methodMap = array(
            'action_object_provider' => 'getActionObjectProviderService',
            'angular' => 'getAngularService',
            'asset_builder' => 'getAssetBuilderService',
            'cache.checks' => 'getCache_ChecksService',
            'cache.community_messages' => 'getCache_CommunityMessagesService',
            'cache.contacttypes' => 'getCache_ContacttypesService',
            'cache.customdata' => 'getCache_CustomdataService',
            'cache.default' => 'getCache_DefaultService',
            'cache.fields' => 'getCache_FieldsService',
            'cache.groups' => 'getCache_GroupsService',
            'cache.js_strings' => 'getCache_JsStringsService',
            'cache.long' => 'getCache_LongService',
            'cache.metadata' => 'getCache_MetadataService',
            'cache.navigation' => 'getCache_NavigationService',
            'cache.prevnextcache' => 'getCache_PrevnextcacheService',
            'cache.session' => 'getCache_SessionService',
            'cache.settings' => 'getCache_SettingsService',
            'cache_config' => 'getCacheConfigService',
            'civi.activity.triggers' => 'getCivi_Activity_TriggersService',
            'civi.case.statictriggers' => 'getCivi_Case_StatictriggersService',
            'civi.case.triggers' => 'getCivi_Case_TriggersService',
            'civi.mailing.triggers' => 'getCivi_Mailing_TriggersService',
            'civi_api4_event_subscriber_activityprecreationsubscriber' => 'getCiviApi4EventSubscriberActivityprecreationsubscriberService',
            'civi_api4_event_subscriber_activityschemamapsubscriber' => 'getCiviApi4EventSubscriberActivityschemamapsubscriberService',
            'civi_api4_event_subscriber_contactpresavesubscriber' => 'getCiviApi4EventSubscriberContactpresavesubscriberService',
            'civi_api4_event_subscriber_contactschemamapsubscriber' => 'getCiviApi4EventSubscriberContactschemamapsubscriberService',
            'civi_api4_event_subscriber_contributionpresavesubscriber' => 'getCiviApi4EventSubscriberContributionpresavesubscriberService',
            'civi_api4_event_subscriber_customfieldpresavesubscriber' => 'getCiviApi4EventSubscriberCustomfieldpresavesubscriberService',
            'civi_api4_event_subscriber_customgroupprecreationsubscriber' => 'getCiviApi4EventSubscriberCustomgroupprecreationsubscriberService',
            'civi_api4_event_subscriber_iscurrentsubscriber' => 'getCiviApi4EventSubscriberIscurrentsubscriberService',
            'civi_api4_event_subscriber_optionvalueprecreationsubscriber' => 'getCiviApi4EventSubscriberOptionvalueprecreationsubscriberService',
            'civi_api4_event_subscriber_permissionchecksubscriber' => 'getCiviApi4EventSubscriberPermissionchecksubscriberService',
            'civi_api4_event_subscriber_postselectquerysubscriber' => 'getCiviApi4EventSubscriberPostselectquerysubscriberService',
            'civi_api4_event_subscriber_validatefieldssubscriber' => 'getCiviApi4EventSubscriberValidatefieldssubscriberService',
            'civi_api4_service_spec_provider_aclcreationspecprovider' => 'getCiviApi4ServiceSpecProviderAclcreationspecproviderService',
            'civi_api4_service_spec_provider_actionschedulecreationspecprovider' => 'getCiviApi4ServiceSpecProviderActionschedulecreationspecproviderService',
            'civi_api4_service_spec_provider_activitycreationspecprovider' => 'getCiviApi4ServiceSpecProviderActivitycreationspecproviderService',
            'civi_api4_service_spec_provider_addresscreationspecprovider' => 'getCiviApi4ServiceSpecProviderAddresscreationspecproviderService',
            'civi_api4_service_spec_provider_campaigncreationspecprovider' => 'getCiviApi4ServiceSpecProviderCampaigncreationspecproviderService',
            'civi_api4_service_spec_provider_contactcreationspecprovider' => 'getCiviApi4ServiceSpecProviderContactcreationspecproviderService',
            'civi_api4_service_spec_provider_contacttypecreationspecprovider' => 'getCiviApi4ServiceSpecProviderContacttypecreationspecproviderService',
            'civi_api4_service_spec_provider_contributioncreationspecprovider' => 'getCiviApi4ServiceSpecProviderContributioncreationspecproviderService',
            'civi_api4_service_spec_provider_customfieldcreationspecprovider' => 'getCiviApi4ServiceSpecProviderCustomfieldcreationspecproviderService',
            'civi_api4_service_spec_provider_customgroupcreationspecprovider' => 'getCiviApi4ServiceSpecProviderCustomgroupcreationspecproviderService',
            'civi_api4_service_spec_provider_customvaluespecprovider' => 'getCiviApi4ServiceSpecProviderCustomvaluespecproviderService',
            'civi_api4_service_spec_provider_defaultlocationtypeprovider' => 'getCiviApi4ServiceSpecProviderDefaultlocationtypeproviderService',
            'civi_api4_service_spec_provider_domaincreationspecprovider' => 'getCiviApi4ServiceSpecProviderDomaincreationspecproviderService',
            'civi_api4_service_spec_provider_emailcreationspecprovider' => 'getCiviApi4ServiceSpecProviderEmailcreationspecproviderService',
            'civi_api4_service_spec_provider_entitytagcreationspecprovider' => 'getCiviApi4ServiceSpecProviderEntitytagcreationspecproviderService',
            'civi_api4_service_spec_provider_eventcreationspecprovider' => 'getCiviApi4ServiceSpecProviderEventcreationspecproviderService',
            'civi_api4_service_spec_provider_fielddomainidspecprovider' => 'getCiviApi4ServiceSpecProviderFielddomainidspecproviderService',
            'civi_api4_service_spec_provider_getactiondefaultsprovider' => 'getCiviApi4ServiceSpecProviderGetactiondefaultsproviderService',
            'civi_api4_service_spec_provider_groupcreationspecprovider' => 'getCiviApi4ServiceSpecProviderGroupcreationspecproviderService',
            'civi_api4_service_spec_provider_mappingcreationspecprovider' => 'getCiviApi4ServiceSpecProviderMappingcreationspecproviderService',
            'civi_api4_service_spec_provider_navigationspecprovider' => 'getCiviApi4ServiceSpecProviderNavigationspecproviderService',
            'civi_api4_service_spec_provider_notecreationspecprovider' => 'getCiviApi4ServiceSpecProviderNotecreationspecproviderService',
            'civi_api4_service_spec_provider_optionvaluecreationspecprovider' => 'getCiviApi4ServiceSpecProviderOptionvaluecreationspecproviderService',
            'civi_api4_service_spec_provider_paymentprocessorcreationspecprovider' => 'getCiviApi4ServiceSpecProviderPaymentprocessorcreationspecproviderService',
            'civi_api4_service_spec_provider_paymentprocessortypecreationspecprovider' => 'getCiviApi4ServiceSpecProviderPaymentprocessortypecreationspecproviderService',
            'civi_api4_service_spec_provider_phonecreationspecprovider' => 'getCiviApi4ServiceSpecProviderPhonecreationspecproviderService',
            'civi_api4_service_spec_provider_relationshiptypecreationspecprovider' => 'getCiviApi4ServiceSpecProviderRelationshiptypecreationspecproviderService',
            'civi_api4_service_spec_provider_tagcreationspecprovider' => 'getCiviApi4ServiceSpecProviderTagcreationspecproviderService',
            'civi_api4_service_spec_provider_uffieldcreationspecprovider' => 'getCiviApi4ServiceSpecProviderUffieldcreationspecproviderService',
            'civi_api_kernel' => 'getCiviApiKernelService',
            'civi_container_factory' => 'getCiviContainerFactoryService',
            'civi_token_compat' => 'getCiviTokenCompatService',
            'crm_activity_tokens' => 'getCrmActivityTokensService',
            'crm_contribute_tokens' => 'getCrmContributeTokensService',
            'crm_event_tokens' => 'getCrmEventTokensService',
            'crm_mailing_action_tokens' => 'getCrmMailingActionTokensService',
            'crm_mailing_tokens' => 'getCrmMailingTokensService',
            'crm_member_tokens' => 'getCrmMemberTokensService',
            'cxn_reg_client' => 'getCxnRegClientService',
            'dispatcher' => 'getDispatcherService',
            'httpclient' => 'getHttpclientService',
            'i18n' => 'getI18nService',
            'joiner' => 'getJoinerService',
            'lockmanager' => 'getLockmanagerService',
            'magic_function_provider' => 'getMagicFunctionProviderService',
            'paths' => 'getPathsService',
            'pear_mail' => 'getPearMailService',
            'prevnext' => 'getPrevnextService',
            'prevnext.driver.redis' => 'getPrevnext_Driver_RedisService',
            'prevnext.driver.sql' => 'getPrevnext_Driver_SqlService',
            'psr_log' => 'getPsrLogService',
            'resources' => 'getResourcesService',
            'runtime' => 'getRuntimeService',
            'schema_map' => 'getSchemaMapService',
            'settings_manager' => 'getSettingsManagerService',
            'spec_gatherer' => 'getSpecGathererService',
            'sql_triggers' => 'getSqlTriggersService',
            'themes' => 'getThemesService',
            'userpermissionclass' => 'getUserpermissionclassService',
            'usersystem' => 'getUsersystemService',
        );
        $this->aliases = array(
            'cache.short' => 'cache.default',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        throw new LogicException('You cannot compile a dumped frozen container.');
    }

    /**
     * {@inheritdoc}
     */
    public function isFrozen()
    {
        return true;
    }

    /**
     * Gets the public 'action_object_provider' shared service.
     *
     * @return \Civi\Api4\Provider\ActionObjectProvider
     */
    protected function getActionObjectProviderService()
    {
        return $this->services['action_object_provider'] = new \Civi\Api4\Provider\ActionObjectProvider();
    }

    /**
     * Gets the public 'angular' shared service.
     *
     * @return \Civi\Angular\Manager
     */
    protected function getAngularService()
    {
        return $this->services['angular'] = $this->get('civi_container_factory')->createAngularManager();
    }

    /**
     * Gets the public 'asset_builder' shared service.
     *
     * @return \Civi\Core\AssetBuilder
     */
    protected function getAssetBuilderService()
    {
        return $this->services['asset_builder'] = new \Civi\Core\AssetBuilder();
    }

    /**
     * Gets the public 'cache.checks' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_ChecksService()
    {
        return $this->services['cache.checks'] = \CRM_Utils_Cache::create(array('name' => 'checks', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache')));
    }

    /**
     * Gets the public 'cache.community_messages' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_CommunityMessagesService()
    {
        return $this->services['cache.community_messages'] = \CRM_Utils_Cache::create(array('name' => 'community_messages', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache')));
    }

    /**
     * Gets the public 'cache.contacttypes' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_ContacttypesService()
    {
        return $this->services['cache.contacttypes'] = \CRM_Utils_Cache::create(array('name' => 'contactTypes', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'), 'withArray' => 'fast'));
    }

    /**
     * Gets the public 'cache.customdata' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_CustomdataService()
    {
        return $this->services['cache.customdata'] = \CRM_Utils_Cache::create(array('name' => 'custom data', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'), 'withArray' => 'fast'));
    }

    /**
     * Gets the public 'cache.default' shared service.
     *
     * @return \CRM_Utils_Cache
     */
    protected function getCache_DefaultService()
    {
        return $this->services['cache.default'] = \CRM_Utils_Cache::singleton();
    }

    /**
     * Gets the public 'cache.fields' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_FieldsService()
    {
        return $this->services['cache.fields'] = \CRM_Utils_Cache::create(array('name' => 'contact fields', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'), 'withArray' => 'fast'));
    }

    /**
     * Gets the public 'cache.groups' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_GroupsService()
    {
        return $this->services['cache.groups'] = \CRM_Utils_Cache::create(array('name' => 'contact groups', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'), 'withArray' => 'fast'));
    }

    /**
     * Gets the public 'cache.js_strings' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_JsStringsService()
    {
        return $this->services['cache.js_strings'] = \CRM_Utils_Cache::create(array('name' => 'js_strings', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache')));
    }

    /**
     * Gets the public 'cache.long' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_LongService()
    {
        return $this->services['cache.long'] = \CRM_Utils_Cache::create(array('name' => 'long', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache')));
    }

    /**
     * Gets the public 'cache.metadata' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_MetadataService()
    {
        return $this->services['cache.metadata'] = \CRM_Utils_Cache::create(array('name' => 'metadata', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'), 'withArray' => 'fast'));
    }

    /**
     * Gets the public 'cache.navigation' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_NavigationService()
    {
        return $this->services['cache.navigation'] = \CRM_Utils_Cache::create(array('name' => 'navigation', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'), 'withArray' => 'fast'));
    }

    /**
     * Gets the public 'cache.prevnextcache' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_PrevnextcacheService()
    {
        return $this->services['cache.prevnextcache'] = \CRM_Utils_Cache::create(array('name' => 'CiviCRM Search PrevNextCache', 'type' => array(0 => 'SqlGroup')));
    }

    /**
     * Gets the public 'cache.session' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_SessionService()
    {
        return $this->services['cache.session'] = \CRM_Utils_Cache::create(array('name' => 'CiviCRM Session', 'type' => array(0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache')));
    }

    /**
     * Gets the public 'cache.settings' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getCache_SettingsService()
    {
        throw new RuntimeException('You have requested a synthetic service ("cache.settings"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the public 'cache_config' shared service.
     *
     * @return \ArrayObject
     */
    protected function getCacheConfigService()
    {
        return $this->services['cache_config'] = $this->get('civi_container_factory')->createCacheConfig();
    }

    /**
     * Gets the public 'civi.activity.triggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\TimestampTriggers
     */
    protected function getCivi_Activity_TriggersService()
    {
        return $this->services['civi.activity.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_activity', 'Activity');
    }

    /**
     * Gets the public 'civi.case.statictriggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\StaticTriggers
     */
    protected function getCivi_Case_StatictriggersService()
    {
        return $this->services['civi.case.statictriggers'] = new \Civi\Core\SqlTrigger\StaticTriggers(array(0 => array('upgrade_check' => array('table' => 'civicrm_case', 'column' => 'modified_date'), 'table' => 'civicrm_case_activity', 'when' => 'AFTER', 'event' => array(0 => 'INSERT'), 'sql' => ''."\n".'UPDATE civicrm_case SET modified_date = CURRENT_TIMESTAMP WHERE id = NEW.case_id;'."\n".''), 1 => array('upgrade_check' => array('table' => 'civicrm_case', 'column' => 'modified_date'), 'table' => 'civicrm_activity', 'when' => 'BEFORE', 'event' => array(0 => 'UPDATE', 1 => 'DELETE'), 'sql' => ''."\n".'UPDATE civicrm_case SET modified_date = CURRENT_TIMESTAMP WHERE id IN (SELECT ca.case_id FROM civicrm_case_activity ca WHERE ca.activity_id = OLD.id);'."\n".'')));
    }

    /**
     * Gets the public 'civi.case.triggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\TimestampTriggers
     */
    protected function getCivi_Case_TriggersService()
    {
        return $this->services['civi.case.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_case', 'Case');
    }

    /**
     * Gets the public 'civi.mailing.triggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\TimestampTriggers
     */
    protected function getCivi_Mailing_TriggersService()
    {
        return $this->services['civi.mailing.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_mailing', 'Mailing');
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_activityprecreationsubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ActivityPreCreationSubscriber
     */
    protected function getCiviApi4EventSubscriberActivityprecreationsubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_activityprecreationsubscriber'] = new \Civi\Api4\Event\Subscriber\ActivityPreCreationSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_activityschemamapsubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ActivitySchemaMapSubscriber
     */
    protected function getCiviApi4EventSubscriberActivityschemamapsubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_activityschemamapsubscriber'] = new \Civi\Api4\Event\Subscriber\ActivitySchemaMapSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_contactpresavesubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ContactPreSaveSubscriber
     */
    protected function getCiviApi4EventSubscriberContactpresavesubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_contactpresavesubscriber'] = new \Civi\Api4\Event\Subscriber\ContactPreSaveSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_contactschemamapsubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ContactSchemaMapSubscriber
     */
    protected function getCiviApi4EventSubscriberContactschemamapsubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_contactschemamapsubscriber'] = new \Civi\Api4\Event\Subscriber\ContactSchemaMapSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_contributionpresavesubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ContributionPreSaveSubscriber
     */
    protected function getCiviApi4EventSubscriberContributionpresavesubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_contributionpresavesubscriber'] = new \Civi\Api4\Event\Subscriber\ContributionPreSaveSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_customfieldpresavesubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\CustomFieldPreSaveSubscriber
     */
    protected function getCiviApi4EventSubscriberCustomfieldpresavesubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_customfieldpresavesubscriber'] = new \Civi\Api4\Event\Subscriber\CustomFieldPreSaveSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_customgroupprecreationsubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\CustomGroupPreCreationSubscriber
     */
    protected function getCiviApi4EventSubscriberCustomgroupprecreationsubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_customgroupprecreationsubscriber'] = new \Civi\Api4\Event\Subscriber\CustomGroupPreCreationSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_iscurrentsubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\IsCurrentSubscriber
     */
    protected function getCiviApi4EventSubscriberIscurrentsubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_iscurrentsubscriber'] = new \Civi\Api4\Event\Subscriber\IsCurrentSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_optionvalueprecreationsubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\OptionValuePreCreationSubscriber
     */
    protected function getCiviApi4EventSubscriberOptionvalueprecreationsubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_optionvalueprecreationsubscriber'] = new \Civi\Api4\Event\Subscriber\OptionValuePreCreationSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_permissionchecksubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\PermissionCheckSubscriber
     */
    protected function getCiviApi4EventSubscriberPermissionchecksubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_permissionchecksubscriber'] = new \Civi\Api4\Event\Subscriber\PermissionCheckSubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_postselectquerysubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\PostSelectQuerySubscriber
     */
    protected function getCiviApi4EventSubscriberPostselectquerysubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_postselectquerysubscriber'] = new \Civi\Api4\Event\Subscriber\PostSelectQuerySubscriber();
    }

    /**
     * Gets the public 'civi_api4_event_subscriber_validatefieldssubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ValidateFieldsSubscriber
     */
    protected function getCiviApi4EventSubscriberValidatefieldssubscriberService()
    {
        return $this->services['civi_api4_event_subscriber_validatefieldssubscriber'] = new \Civi\Api4\Event\Subscriber\ValidateFieldsSubscriber();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_aclcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ACLCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderAclcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_aclcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\ACLCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_actionschedulecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ActionScheduleCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderActionschedulecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_actionschedulecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\ActionScheduleCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_activitycreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ActivityCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderActivitycreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_activitycreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\ActivityCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_addresscreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\AddressCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderAddresscreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_addresscreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\AddressCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_campaigncreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CampaignCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCampaigncreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_campaigncreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\CampaignCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_contactcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContactCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContactcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_contactcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\ContactCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_contacttypecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContactTypeCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContacttypecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_contacttypecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\ContactTypeCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_contributioncreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContributionCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContributioncreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_contributioncreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\ContributionCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_customfieldcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CustomFieldCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCustomfieldcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_customfieldcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\CustomFieldCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_customgroupcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CustomGroupCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCustomgroupcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_customgroupcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\CustomGroupCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_customvaluespecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CustomValueSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCustomvaluespecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_customvaluespecprovider'] = new \Civi\Api4\Service\Spec\Provider\CustomValueSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_defaultlocationtypeprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\DefaultLocationTypeProvider
     */
    protected function getCiviApi4ServiceSpecProviderDefaultlocationtypeproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_defaultlocationtypeprovider'] = new \Civi\Api4\Service\Spec\Provider\DefaultLocationTypeProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_domaincreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\DomainCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderDomaincreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_domaincreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\DomainCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_emailcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EmailCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEmailcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_emailcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\EmailCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_entitytagcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EntityTagCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEntitytagcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_entitytagcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\EntityTagCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_eventcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EventCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEventcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_eventcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\EventCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_fielddomainidspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\FieldDomainIdSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderFielddomainidspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_fielddomainidspecprovider'] = new \Civi\Api4\Service\Spec\Provider\FieldDomainIdSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_getactiondefaultsprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\GetActionDefaultsProvider
     */
    protected function getCiviApi4ServiceSpecProviderGetactiondefaultsproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_getactiondefaultsprovider'] = new \Civi\Api4\Service\Spec\Provider\GetActionDefaultsProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_groupcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\GroupCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderGroupcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_groupcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\GroupCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_mappingcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\MappingCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderMappingcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_mappingcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\MappingCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_navigationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\NavigationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderNavigationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_navigationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\NavigationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_notecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\NoteCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderNotecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_notecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\NoteCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_optionvaluecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\OptionValueCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderOptionvaluecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_optionvaluecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\OptionValueCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_paymentprocessorcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PaymentProcessorCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPaymentprocessorcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_paymentprocessorcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\PaymentProcessorCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_paymentprocessortypecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PaymentProcessorTypeCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPaymentprocessortypecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_paymentprocessortypecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\PaymentProcessorTypeCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_phonecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PhoneCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPhonecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_phonecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\PhoneCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_relationshiptypecreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\RelationshipTypeCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderRelationshiptypecreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_relationshiptypecreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\RelationshipTypeCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_tagcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\TagCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderTagcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_tagcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\TagCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api4_service_spec_provider_uffieldcreationspecprovider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\UFFieldCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderUffieldcreationspecproviderService()
    {
        return $this->services['civi_api4_service_spec_provider_uffieldcreationspecprovider'] = new \Civi\Api4\Service\Spec\Provider\UFFieldCreationSpecProvider();
    }

    /**
     * Gets the public 'civi_api_kernel' shared service.
     *
     * @return \Civi\API\Kernel
     */
    protected function getCiviApiKernelService()
    {
        $this->services['civi_api_kernel'] = $instance = $this->get('civi_container_factory')->createApiKernel($this->get('dispatcher'), $this->get('magic_function_provider'));

        $instance->registerApiProvider($this->get('action_object_provider'));

        return $instance;
    }

    /**
     * Gets the public 'civi_container_factory' shared service.
     *
     * @return \Civi\Core\Container
     */
    protected function getCiviContainerFactoryService()
    {
        return $this->services['civi_container_factory'] = new \Civi\Core\Container();
    }

    /**
     * Gets the public 'civi_token_compat' shared service.
     *
     * @return \Civi\Token\TokenCompatSubscriber
     */
    protected function getCiviTokenCompatService()
    {
        return $this->services['civi_token_compat'] = new \Civi\Token\TokenCompatSubscriber();
    }

    /**
     * Gets the public 'crm_activity_tokens' shared service.
     *
     * @return \CRM_Activity_Tokens
     */
    protected function getCrmActivityTokensService()
    {
        return $this->services['crm_activity_tokens'] = new \CRM_Activity_Tokens();
    }

    /**
     * Gets the public 'crm_contribute_tokens' shared service.
     *
     * @return \CRM_Contribute_Tokens
     */
    protected function getCrmContributeTokensService()
    {
        return $this->services['crm_contribute_tokens'] = new \CRM_Contribute_Tokens();
    }

    /**
     * Gets the public 'crm_event_tokens' shared service.
     *
     * @return \CRM_Event_Tokens
     */
    protected function getCrmEventTokensService()
    {
        return $this->services['crm_event_tokens'] = new \CRM_Event_Tokens();
    }

    /**
     * Gets the public 'crm_mailing_action_tokens' shared service.
     *
     * @return \CRM_Mailing_ActionTokens
     */
    protected function getCrmMailingActionTokensService()
    {
        return $this->services['crm_mailing_action_tokens'] = new \CRM_Mailing_ActionTokens();
    }

    /**
     * Gets the public 'crm_mailing_tokens' shared service.
     *
     * @return \CRM_Mailing_Tokens
     */
    protected function getCrmMailingTokensService()
    {
        return $this->services['crm_mailing_tokens'] = new \CRM_Mailing_Tokens();
    }

    /**
     * Gets the public 'crm_member_tokens' shared service.
     *
     * @return \CRM_Member_Tokens
     */
    protected function getCrmMemberTokensService()
    {
        return $this->services['crm_member_tokens'] = new \CRM_Member_Tokens();
    }

    /**
     * Gets the public 'cxn_reg_client' shared service.
     *
     * @return \Civi\Cxn\Rpc\RegistrationClient
     */
    protected function getCxnRegClientService()
    {
        return $this->services['cxn_reg_client'] = \CRM_Cxn_BAO_Cxn::createRegistrationClient();
    }

    /**
     * Gets the public 'dispatcher' shared service.
     *
     * @return \Civi\Core\CiviEventDispatcher
     */
    protected function getDispatcherService()
    {
        $this->services['dispatcher'] = $instance = $this->get('civi_container_factory')->createEventDispatcher($this);

        $instance->addSubscriber($this->get('action_object_provider'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_activityprecreationsubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_activityschemamapsubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_contactpresavesubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_contactschemamapsubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_contributionpresavesubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_customfieldpresavesubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_customgroupprecreationsubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_iscurrentsubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_optionvalueprecreationsubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_permissionchecksubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_postselectquerysubscriber'));
        $instance->addSubscriber($this->get('civi_api4_event_subscriber_validatefieldssubscriber'));
        $instance->addListenerService('hook_civicrm_triggerInfo', array(0 => 'civi.mailing.triggers', 1 => 'onTriggerInfo'), 0);
        $instance->addListenerService('hook_civicrm_triggerInfo', array(0 => 'civi.activity.triggers', 1 => 'onTriggerInfo'), 0);
        $instance->addListenerService('hook_civicrm_triggerInfo', array(0 => 'civi.case.triggers', 1 => 'onTriggerInfo'), 0);
        $instance->addListenerService('hook_civicrm_triggerInfo', array(0 => 'civi.case.statictriggers', 1 => 'onTriggerInfo'), 0);
        $instance->addSubscriberService('civi_token_compat', 'Civi\\Token\\TokenCompatSubscriber');
        $instance->addSubscriberService('crm_mailing_action_tokens', 'CRM_Mailing_ActionTokens');
        $instance->addSubscriberService('crm_activity_tokens', 'CRM_Activity_Tokens');
        $instance->addSubscriberService('crm_contribute_tokens', 'CRM_Contribute_Tokens');
        $instance->addSubscriberService('crm_event_tokens', 'CRM_Event_Tokens');
        $instance->addSubscriberService('crm_mailing_tokens', 'CRM_Mailing_Tokens');
        $instance->addSubscriberService('crm_member_tokens', 'CRM_Member_Tokens');

        return $instance;
    }

    /**
     * Gets the public 'httpclient' shared service.
     *
     * @return \CRM_Utils_HttpClient
     */
    protected function getHttpclientService()
    {
        return $this->services['httpclient'] = \CRM_Utils_HttpClient::singleton();
    }

    /**
     * Gets the public 'i18n' shared service.
     *
     * @return \CRM_Core_I18n
     */
    protected function getI18nService()
    {
        return $this->services['i18n'] = \CRM_Core_I18n::singleton();
    }

    /**
     * Gets the public 'joiner' shared service.
     *
     * @return \Civi\Api4\Service\Schema\Joiner
     */
    protected function getJoinerService()
    {
        return $this->services['joiner'] = new \Civi\Api4\Service\Schema\Joiner($this->get('schema_map'));
    }

    /**
     * Gets the public 'lockmanager' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getLockmanagerService()
    {
        throw new RuntimeException('You have requested a synthetic service ("lockmanager"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the public 'magic_function_provider' shared service.
     *
     * @return \Civi\API\Provider\MagicFunctionProvider
     */
    protected function getMagicFunctionProviderService()
    {
        return $this->services['magic_function_provider'] = new \Civi\API\Provider\MagicFunctionProvider();
    }

    /**
     * Gets the public 'paths' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getPathsService()
    {
        throw new RuntimeException('You have requested a synthetic service ("paths"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the public 'pear_mail' shared service.
     *
     * @return \Mail
     */
    protected function getPearMailService()
    {
        return $this->services['pear_mail'] = \CRM_Utils_Mail::createMailer();
    }

    /**
     * Gets the public 'prevnext' shared service.
     *
     * @return \CRM_Core_PrevNextCache_Interface
     */
    protected function getPrevnextService()
    {
        return $this->services['prevnext'] = $this->get('civi_container_factory')->createPrevNextCache($this);
    }

    /**
     * Gets the public 'prevnext.driver.redis' shared service.
     *
     * @return \CRM_Core_PrevNextCache_Redis
     */
    protected function getPrevnext_Driver_RedisService()
    {
        return $this->services['prevnext.driver.redis'] = new \CRM_Core_PrevNextCache_Redis($this->get('cache_config'));
    }

    /**
     * Gets the public 'prevnext.driver.sql' shared service.
     *
     * @return \CRM_Core_PrevNextCache_Sql
     */
    protected function getPrevnext_Driver_SqlService()
    {
        return $this->services['prevnext.driver.sql'] = new \CRM_Core_PrevNextCache_Sql();
    }

    /**
     * Gets the public 'psr_log' shared service.
     *
     * @return \CRM_Core_Error_Log
     */
    protected function getPsrLogService()
    {
        return $this->services['psr_log'] = new \CRM_Core_Error_Log();
    }

    /**
     * Gets the public 'resources' shared service.
     *
     * @return \CRM_Core_Resources
     */
    protected function getResourcesService()
    {
        return $this->services['resources'] = $this->get('civi_container_factory')->createResources($this);
    }

    /**
     * Gets the public 'runtime' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getRuntimeService()
    {
        throw new RuntimeException('You have requested a synthetic service ("runtime"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the public 'schema_map' shared service.
     *
     * @return \Civi\Api4\Service\Schema\SchemaMap
     */
    protected function getSchemaMapService()
    {
        return $this->services['schema_map'] = call_user_func(array(new \Civi\Api4\Service\Schema\SchemaMapBuilder($this->get('dispatcher')), 'build'));
    }

    /**
     * Gets the public 'settings_manager' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getSettingsManagerService()
    {
        throw new RuntimeException('You have requested a synthetic service ("settings_manager"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the public 'spec_gatherer' shared service.
     *
     * @return \Civi\Api4\Service\Spec\SpecGatherer
     */
    protected function getSpecGathererService()
    {
        $this->services['spec_gatherer'] = $instance = new \Civi\Api4\Service\Spec\SpecGatherer();

        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_aclcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_actionschedulecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_activitycreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_addresscreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_campaigncreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_contactcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_contacttypecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_contributioncreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_customfieldcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_customgroupcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_customvaluespecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_defaultlocationtypeprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_domaincreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_emailcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_entitytagcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_eventcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_fielddomainidspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_getactiondefaultsprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_groupcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_mappingcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_navigationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_notecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_optionvaluecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_paymentprocessorcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_paymentprocessortypecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_phonecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_relationshiptypecreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_tagcreationspecprovider'));
        $instance->addSpecProvider($this->get('civi_api4_service_spec_provider_uffieldcreationspecprovider'));

        return $instance;
    }

    /**
     * Gets the public 'sql_triggers' shared service.
     *
     * @return \Civi\Core\SqlTriggers
     */
    protected function getSqlTriggersService()
    {
        return $this->services['sql_triggers'] = new \Civi\Core\SqlTriggers();
    }

    /**
     * Gets the public 'themes' shared service.
     *
     * @return \Civi\Core\Themes
     */
    protected function getThemesService()
    {
        return $this->services['themes'] = new \Civi\Core\Themes();
    }

    /**
     * Gets the public 'userpermissionclass' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getUserpermissionclassService()
    {
        throw new RuntimeException('You have requested a synthetic service ("userpermissionclass"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the public 'usersystem' shared service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getUsersystemService()
    {
        throw new RuntimeException('You have requested a synthetic service ("usersystem"). The DIC does not know how to construct this service.');
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = strtolower($name);

        if (!(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters))) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        $name = strtolower($name);

        return isset($this->parameters[$name]) || array_key_exists($name, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }

        return $this->parameterBag;
    }

    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'civicrm_base_path' => 'C:\\wamp64\\www\\Joomla\\administrator\\components\\com_civicrm\\civicrm',
        );
    }
}
