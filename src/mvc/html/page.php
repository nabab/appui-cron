<!-- HTML Document -->

<bbn-router :autoload="true"
            :nav="true"
>
	<bbns-container :label="_('Home')"
           :loaded="false"
           :load="true"
           icon="nf nf-fa-home"
           :pinned="true"
           url="home">
  </bbns-container>
</bbn-router>