<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Администрирование', 'options' => ['class' => 'header']],
                    ['label' => 'Забеги', 'icon' => 'file-o', 'url' => ['/cabinet/race/index'], 'active' => $this->context->id == 'race/index'],
                    ['label' => 'Треки', 'icon' => 'file-o', 'url' => ['/cabinet/track/index'], 'active' => $this->context->id == 'track/index'],
                    ['label' => 'Товары', 'icon' => 'file-o', 'url' => ['/shop/product/index'], 'active' => $this->context->id == 'product/index'],
                    ['label' => 'Заказы', 'icon' => 'file-o', 'url' => ['/shop/order/index'], 'active' => $this->context->id == 'order/index'],
                    ['label' => 'Скидки', 'icon' => 'file-o', 'url' => ['/shop/discount/index'], 'active' => $this->context->id == 'discount-size/index'],
                    ['label' => 'Пользователи', 'icon' => 'user', 'url' => ['/user/index'], 'active' => $this->context->id == 'user/index'],
                ],
            ]
        ); ?>

    </section>

</aside>
