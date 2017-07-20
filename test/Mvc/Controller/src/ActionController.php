<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Gz3BaseTest\Mvc\Controller\src;

use Gz3Base\Mvc\Controller\ActionController;


class ActionController extends ActionController
{

    /** @var string self::WORD */
    const WORD = 'test';
    /** @var string self::SENTENCE */
    const SENTENCE = 'This is a sentence containing a hyphen-separated word.';


    /**
     * @return string[] $word
     */
    public function wordAction() : array
    {
        return ['content'=>self::WORD];
    }

    /**
     * @return string[] $sentence
     */
    public function sentenceAction() : array
    {
        return ['content'=>self::SENTENCE];
    }

    /**
     * @return unknown $endlessLoop
     */
    public function circularAction()
    {
        return $this->forward()->dispatch('fixture-action', ['action'=>'circular']);
    }
}
