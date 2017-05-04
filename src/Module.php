<?php

namespace luya\contactform;

use Yii;
use luya\Exception;

/**
 * LUYA CONTACT FORM MODULE
 *
 * Example configuration:
 *
 * ```php
 * 'contactform' => [
 *     'class' => 'contactform\Module',
 *     'attributes' => ['name', 'email', 'street', 'city', 'tel', 'message'],
 *     'rules' => [
 *         [['name', 'email', 'street', 'city', 'message'], 'required'],
 *         ['email', 'email'],
 *     ],
 *     'recipients' => ['admin@example.com'],
 * ],
 * ```
 * 
 * @property string $mailTitle The mail title property.
 *
 * @author nadar
 * @since 1.0.0-beta6
 */
class Module extends \luya\base\Module
{
    /**
     * @var boolean By default this module will lookup the view files in the appliation view folder instead of
     * the module base path views folder.
     */
    public $useAppViewPath = true;
    
    /**
     * @var array An array containing all the attributes for this model
     *
     * ```
     * 'attributes' => ['name', 'email', 'street', 'city', 'tel', 'message'],
     * ```
     */
    public $attributes = null;
    
    /**
     * @var array An array of detail view attributes based to the {{yii\widgets\DetailView::attributes}} in order to
     * customize the mail table which is rendered trough {{yii\widgets\DetailView}}.
     * @since 1.0.2
     */
    public $detailViewAttributes;
    
    /**
     * @var array An array define the attribute labels for an attribute, internal the attribute label values
     * will be wrapped into the `Yii::t()` method.
     *
     * ```
     * 'attributeLabels' => [
     *     'email' => 'E-Mail-Adresse',
     * ],
     * ```
     */
    public $attributeLabels = [];
    
    /**
     * @var array An array define the rules for the corresponding attributes. Example rules:
     *
     * ```php
     * rules' => [
     *     [['name', 'email', 'street', 'city', 'message'], 'required'],
     *     ['email', 'email'],
     * ],
     * ```
     */
    public $rules = [];
    
    /**
     * @var callable You can define a anonmys function which will be trigger on success, the first parameter of the
     * function can be the model which will be assigned [[\luya\base\DynamicModel]]. Example callback
     *
     * ```php
     * $callback = function($model) {
     *     // insert the name of each contact form into `contact_form_requests` table:
     *     Yii::$db->createCommand()->insert('contact_form_requests', ['name' => $model->name])->execute();
     * }
     * ```
     */
    public $callback = null;
    
    /**
     *@var array An array with all recipients the mail should be sent on success, recipients will be assigned via
     * {{\luya\components\Mail::adresses}} method of the mailer function.
     */
    public $recipients = null;
    
    /**
     * @var int Number in seconds, if the process time is faster then `$spamDetectionDelay`, the mail will threated as spam
     * and throws an exception. As humans requires at least more then 2 seconds to fillup a form we use this as base value.
     */
    public $spamDetectionDelay = 2;
    
    /**
     * @var string If you like to enable that the same email for $recipients is going to be sent to the customer which enters form provide the attribute name
     * for the email adresse from the $model configuration. Assuming you have an attribute 'email' in your configuration attributes you have to provide this name.
     * 
     * ```php
     * 'sendToUserEmail' => 'email',
     * ```
     */
    public $sendToUserEmail = false;
    
    /**
     * @var string Markdown enabled text which can be prepand to the E-Mail Data body.
     */
    public $mailText = null;
    
    /**
     * {@inheritDoc}
     * @see \luya\base\Module::init()
     */
    public function init()
    {
        parent::init();
        
        if ($this->attributes === null) {
            throw new Exception("The attributes attributed must be defined with an array of available attributes.");
        }
        
        if ($this->recipients === null) {
            throw new Exception("The recipients attributed must be defined with an array of recipients who will recieve an email.");
        }
    }
    
    private $_mailTitle = null;
    
    /**
     * Getter method for $mailTitle.
     * 
     * @return string
     */
    public function getMailTitle()
    {
    	if ($this->_mailTitle === null) {
    		$this->_mailTitle = '['.Yii::$app->siteTitle.'] Contact Request';
    	}
    	 
    	return $this->_mailTitle;
    }
    
    /**
     * Setter method fro $mailTitle.
     * 
     * @param string $title The mail title text.
     */
    public function setMailTitle($title)
    {
    	$this->_mailTitle = $title;
    }    
}
