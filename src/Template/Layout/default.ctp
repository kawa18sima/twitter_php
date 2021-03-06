<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('header.css') ?>
    <?= $this->Html->script('http://code.jquery.com/jquery-1.11.3.min.js'); ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <div class='header__logo'>
            <?= $this->Html->image('twitter.jpeg',['alt' => 'Twitters', 'width'=>'200','height'=>'100', 'url' => ['controller' => 'messages', 'action' => 'index']]) ?>
        </div>
        <div class='header__menu'>
            <?= $this->Html->link('ホーム', ['controller' => 'messages', 'action' => 'index']) ?>
            <?php if(isset($user)): ?>
                <?= $this->Html->link('友達を検索',['controller' => 'users','action' => 'find']) ?>
                <?= $this->Html->link('ログアウト',['controller' => 'users','action' => 'logout']) ?>
            <?php else: ?>
                <?= $this->Html->link('ユーザ登録', ['controller' => 'users', 'action' => 'signup']) ?>
                <?= $this->Html->link('ログイン', ['controller' => 'users', 'action' => 'login']) ?>
            <?php endif ?>
        </div>
        
    </header><!-- /header -->
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
