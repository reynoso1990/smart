@extends('tenant.layouts.auth')

@section('content')
<div id="root" class="h-100">
    <!-- Background Start -->
    <div class="fixed-background"></div>
    <!-- Background End -->
    <div class="container-fluid p-0 h-100 position-relative">
        <div class="row g-0 h-100">
          <!-- Left Side Start -->
          <?php
          $text_style= $vc_config->view_tutorials==true ? "text-dark" : "text-white";
          ?>
          @if($vc_videos!=null && $vc_config->view_tutorials)
          <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
            <div class="d-flex align-items-center w-100">
              <div class="w-100">
               
                  <p class="h6 {{$text_style}}">
                    <section class="scroll-section mx-auto" id="youtube">
                         <div class="row">
                          <div class="col-12 col-md-12 col-xxl-7 mx-auto">
                            <div class="card bg-transparent">
                              <div class="plyr__video-embed player">
                                <?php
                                  $video_url = $vc_videos->link."?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1";
                                ?>
                                <iframe
                                  src="{{$video_url}}"
                                  allowfullscreen
                                  allow="autoplay"
                                ></iframe>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                  </p>
                  @if($vc_shortcuts_left!=null && $vc_config->view_tutorials)
                  <div class="row justify-content-md-center">
                    @foreach ($vc_shortcuts_left as $data)
                    <div class="col-md-3">
                         
                        <a href="{{$data->link}}" target="_blank">
                            <div class="card p-0">
                            <div class="card-body text-center align-items-center d-flex flex-column justify-content-between ">
                                <div class="d-flex rounded-xl bg-gradient-light sw-6 sh-6 justify-content-center align-items-center">
                                    <?php 
                                      $image_logo = asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'shortcuts'.DIRECTORY_SEPARATOR.$data->image);
                                     ?>
                                    <img src="{{$image_logo}}" class="sw-7 sh-7 me-1 mb-1 d-inline-block bg-separator d-flex d-flex justify-content-center rounded-xl d-flex justify-content-center" alt="thumb" />
                                </div>
                                <p class="card-text d-flex {{$text_style}} font-weight-bold">
                                  {{ strtoupper($data->title)  }}
                                </p>
                            </div>
                            </div>
                        </a>
                      </div>
                    @endforeach 
                   </div>
                   @endif

                
              </div>
            </div>
          </div>
          <!-- Left Side End -->
          @endif
          <!-- Right Side Start -->
          <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
            <?php
            $text_style= $vc_config->view_tutorials==true ? "text-dark" : "text-white";
            $bg_foreground = $vc_config->view_tutorials==true ? "bg-foreground" : "";
            ?>
            <div style="padding:150px;" class=" sw-lg-70 min-h-100 d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border {{$bg_foreground}}">
                <div class="sw-lg-50 px-5">
                    <div class="sh-11">
                        <a href="javascript:void(0)">
                            <?php
                            $logo = $company->logo !=null ? "storage/uploads/logos/{$company->logo}" : "logo/logo-blue-light.svg" 
                            ?>
                             <img src="{{$logo}}" height="70px">
                        </a>
                    </div>
                    <div class="text-center">
                        <h1 class="auth__title {{$text_style}}">Bienvenido a<br>{{ $company->trade_name }}</h1>
                        <p class="{{$text_style}}">Ingresa a tu cuenta</p>
                    </div>
                    <div class="bg-card-login">
    
                   
                        <div>
                            <form id="resetForm" class="tooltip-end-bottom text-end" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3 filled">
                                    <i data-cs-icon="email"></i>
                                    <input id="email" type="email" placeholder="Correo Electronico" name="email" class="form-control" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <label class="error text-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </label>
                                    @endif
                                </div>
                                <div class="mb-3 filled">
                                    <i data-cs-icon="lock"></i>
                                    <input name="password" type="password" placeholder="Contraseña" class="form-control">
                                    @if ($errors->has('password'))
                                    <label class="error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </label>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-lg btn-primary">Ingresar</button>
                            </form>
                        </div>
                        @if($vc_shortcuts_right!=null && $vc_config->view_tutorials)
                        <div class="mt-3 filled w-100 text-center">
                           
                           <div class="row justify-content-md-center">
                            @foreach ( $vc_shortcuts_right as $data)
                            <div class="col-md-4">
                                <a href="{{$data->link}}" target="_blank">
                                    <div class="card bg-transparent">
                                    <div class="card-body text-center align-items-center d-flex flex-column justify-content-between ">
                                        <div class="d-flex rounded-xl bg-gradient-light sw-6 sh-6 mb-3 justify-content-center align-items-center">
                                          <?php 
                                              $image_logo = asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'shortcuts'.DIRECTORY_SEPARATOR.$data->image);
                                         ?>
                                            <img src="{{$image_logo}}" class="sw-7 sh-7 me-1 mb-1 d-inline-block bg-separator d-flex d-flex justify-content-center rounded-xl d-flex justify-content-center" alt="thumb" />
                                        </div>
                                        <p class="card-text mb-2 d-flex {{$text_style}} font-weight-bold">
                                          {{ strtoupper($data->title)  }}
                                        </p>
                                    </div>
                                    </div>
                                </a>
                             </div>
                              @endforeach  
                           </div>
                        </div>
                        @endif
                    </div>
    
                </div>
            </div>
          </div>
          <!-- Right Side End -->
        </div>
      </div>
 </div>
@endsection