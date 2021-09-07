import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule }    from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';

import { FrontLayoutRoutingModule } from './front-layout-routing.module';
import { FrontLayoutComponent } from './front-layout.component';
import { LoginComponent } from './login/login.component';
import { SignupComponent } from './signup/signup.component';




@NgModule({
  declarations: [
  	FrontLayoutComponent, 
  	LoginComponent, 
  	SignupComponent
  	
  ],
  imports: [
    CommonModule,
    FrontLayoutRoutingModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule
  ]
})
export class FrontLayoutModule { }
