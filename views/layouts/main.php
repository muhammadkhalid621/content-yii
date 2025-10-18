<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name ?: 'CMS',
            'brandUrl'   => Yii::$app->homeUrl,
            'options'    => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        ]);

        $menuItemsLeft = [
            ['label' => 'Home', 'url' => ['/site/index']],
        ];

        // Only show these when logged in
        if (!Yii::$app->user->isGuest) {
            $menuItemsLeft[] = ['label' => 'Articles', 'url' => ['/content/index']];
            $menuItemsLeft[] = ['label' => 'Files', 'url' => ['/file/index']];
            $menuItemsLeft[] = ['label' => 'Audit', 'url' => ['/audit/index']];
            // Optional:
            // $menuItemsLeft[] = ['label' => 'About', 'url' => ['/site/about']];
            // $menuItemsLeft[] = ['label' => 'Contact', 'url' => ['/site/contact']];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items'   => $menuItemsLeft,
        ]);

        // Right side: Login (guest) or Logout (auth)
        if (Yii::$app->user->isGuest) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items'   => [
                    ['label' => 'Login', 'url' => ['/site/login']],
                ],
            ]);
        } else {
            echo Html::beginTag('div', ['class' => 'd-flex align-items-center']);
            echo Html::tag(
                'span',
                'Signed in as ' . Html::encode(Yii::$app->user->identity->username),
                ['class' => 'navbar-text me-3']
            );

            // CSRF-protected POST logout
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']);
            echo Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken());
            echo Html::submitButton(
                'Logout',
                ['class' => 'btn btn-outline-light btn-sm']
            );
            echo Html::endForm();

            echo Html::endTag('div');
        }

        NavBar::end();
        ?>

        <div class="alert alert-info mb-0">
            tenant: <?= (int)(Yii::$app->tenant->id ?? 0) ?> |
            isGuest: <?= Yii::$app->user->isGuest ? 'yes' : 'no' ?>
            <?php if (!Yii::$app->user->isGuest): ?>
                | userId: <?= (int)Yii::$app->user->id ?> | userTenant: <?= (int)Yii::$app->user->identity->tenant_id ?>
            <?php endif; ?>
        </div>


        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <div class="alert alert-<?= Html::encode($type) ?> alert-dismissible fade show mt-3 mb-0 rounded-0" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main" style="padding-top: 70px;">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>