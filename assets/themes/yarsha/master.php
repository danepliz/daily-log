<?php
echo get_header();
$this->load->theme('common/brand');
?>

<div class="wrapper row-offcanvas row-offcanvas-left">

    <aside class="left-side sidebar-offcanvas">
        <?php echo tb_getMainNav(); ?>
    </aside>

    <aside class="right-side">
        <section class="content-header">
            <h1><?php echo ( isset($page_title) ) ? $page_title : '&nbsp'; ?></h1>
            <ol class="breadcrumb"> <?php echo $this->breadcrumb->output(); ?> </ol>
        </section>


        <?php if( isset($critical_alerts) || isset($feedback) || $validation_errors = validation_errors('<p>','</p>') ){ ?>
        <section class="system-message">

            <?php
            if(isset($critical_alerts)){
                foreach($critical_alerts as $type => $msg){
                    $output = '<div class="alert alert-warning alert-dismissable">';
                    $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                    $output .= $msg;
                    $output .= '</div>';
                    echo $output;
                }
            }

            if(isset($feedback)){
                foreach($feedback as $type => $messages){
                    if(count($messages) > 0){
                        $class = ($type == 'error')? 'danger' : $type;
                        $output = '<div class="alert ff alert-'.$class.' alert-dismissable">';
                        $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';

                        foreach($messages as $msg)
                            $output .= '<p>'.$msg.'</p>';

                        $output .= '</div>';
                        echo $output;
                    }
                }
            }

            ?>
            <?php
            if($validation_errors = validation_errors('<p>','</p>'))
                echo '<div class="alert vv alert-danger alert-dismissable">'.$validation_errors.'</div>';
            else echo "&nbsp;";
            ?>

        </section>
        <?php } ?>

        <section class="content">
            <?php if (isset($maincontent)) $this->load->theme($maincontent); ?>
        </section>
    </aside>

</div>

<?php echo get_footer(); ?>