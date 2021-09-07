<?php

class MY_Loader extends CI_Loader {
    public function template($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
        $content  = $this->view('template/header', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('template/footer', $vars, $return);
        return $content;
    else:
       $this->view('template/header', $vars);
        $this->view($template_name, $vars);
        $this->view('template/footer', $vars);
    endif;
    }


    public function loginTemplate($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
        $content  = $this->view('template/login_template/header', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('template/login_template/footer', $vars, $return);
        return $content;
    else:
       $this->view('template/login_template/header', $vars);
        $this->view($template_name, $vars);
        $this->view('template/login_template/footer', $vars);
    endif;
    }

     public function dashboardTemplate($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
        $content  = $this->view('template/dashboard_template/header', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('template/dashboard_template/footer', $vars, $return);
        return $content;
    else:
       $this->view('template/dashboard_template/header', $vars);
        $this->view($template_name, $vars);
        $this->view('template/dashboard_template/footer', $vars);
    endif;
    }

    public function RenewaldashboardTemplate($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
        $content  = $this->view('template/dashboard_template/renewal_header', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('template/dashboard_template/renewal_footer', $vars, $return);
        return $content;
    else:
       $this->view('template/dashboard_template/renewal_header', $vars);
        $this->view($template_name, $vars);
        $this->view('template/dashboard_template/renewal_footer', $vars);
    endif;
    }

     public function planTemplate($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
        $content  = $this->view('template/plan_template/header', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('template/plan_template/footer', $vars, $return);
        return $content;
    else:
       $this->view('template/plan_template/header', $vars);
        $this->view($template_name, $vars);
        $this->view('template/plan_template/footer', $vars);
    endif;
    }

}


?>