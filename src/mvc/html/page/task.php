<bbn-router :autoload="true"
            mode="tabs"
>
	<bbns-container :label="_('Home')"
                  :load="false"
                  icon="nf nf-fa-home"
                  url="home"
                  component="appui-cron-task"
                  :source="source"
                  :notext="true"
                  :fixed="true"
  ></bbns-container>
</bbn-router>