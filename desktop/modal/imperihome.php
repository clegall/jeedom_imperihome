<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<a class="btn btn-success pull-right bt_saveISSConfig"><i class="fa fa-floppy-o"></i> Sauvegarder</a><br>
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#device" aria-controls="home" role="tab" data-toggle="tab">{{Equipement}}</a></li>
	<li role="presentation"><a href="#scene" aria-controls="home" role="tab" data-toggle="tab">{{Scénario}}</a></li>
	<li role="presentation" class="expertModeVisible"><a href="#advanced" role="tab" data-toggle="tab">{{Mode avancé}}</a></li>
	<li role="presentation" class="expertModeVisible"><a href="#brute" role="tab" data-toggle="tab">{{Config Brute}}</a></li>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="device">
		<table class="table table-bordered table-condensed tablesorter">
			<thead>
				<tr>
					<th>{{Objet}}</th>
					<th>{{Equipement}}</th>
					<th>{{Type}}</th>
					<th>{{Commande}}</th>
					<th>{{Transmettre}}</th>
					<th>{{Type Imperihome}}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$ISSStructure = imperihome::getIssStructure();
				foreach (eqLogic::all() as $eqLogic) {
					if ($eqLogic->getIsEnable() == 0) {
						continue;
					}
					$object = $eqLogic->getObject();
					if (is_object($object) && $object->getIsVisible() == 0) {
						continue;
					}
					$firstLine = true;
					$cmds = $eqLogic->getCmd('info');
					$count = 0;
					foreach ($cmds as $cmd) {
						if (method_exists($cmd, 'imperihomeCmd') && !$cmd->imperihomeCmd()) {
							continue;
						}
						$count++;
					}
					foreach ($cmds as $cmd) {
						if (method_exists($cmd, 'imperihomeCmd') && !$cmd->imperihomeCmd()) {
							continue;
						}
						if ($firstLine) {
							$firstLine = false;
							echo '<tr class="imperihome" data-cmd_id="' . $cmd->getId() . '">';
							echo '<td rowspan="' . $count . '">';
							if (is_object($object)) {
								echo $object->getName();
							} else {
								echo __('Aucun', __FILE__);
							}
							echo '</td>';
							echo '<td rowspan="' . $count . '">';
							echo $eqLogic->getName();
							echo '</td>';
							echo '<td rowspan="' . $count . '">';
							echo $eqLogic->getEqType_name();
							echo '</td>';
						} else {
							echo '<tr class="tablesorter-childRow imperihome" data-cmd_id="' . $cmd->getId() . '">';
						}
						echo '<td>';
						echo $cmd->getName();
						echo '</td>';
						echo '<td style="text-align: center;">';
						echo '<input type="checkbox" class="imperihomeAttr" data-l1key="cmd_transmit"/>';
						echo '</td>';
						echo '<td>';
						echo '<span class="label label-info" style="font-size : 1em;">' . imperihome::convertType($cmd, $ISSStructure) . '</span>';
						echo '</td>';
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="scene">
		<table class="table table-bordered table-condensed tablesorter">
			<thead>
				<tr>
					<th>{{Objet}}</th>
					<th>{{Equipement}}</th>
					<th>{{Type}}</th>
					<th>{{Transmettre}}</th>
					<th>{{Type Imperihome}}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach (scenario::all() as $scenario) {
					$object = $scenario->getObject();
					echo '<tr class="imperihomeScenario" data-scenario_id="' . $scenario->getId() . '">';
					echo '<td>';
					if (is_object($object)) {
						echo $object->getName();
					} else {
						echo __('Aucun', __FILE__);
					}
					echo '</td>';
					echo '<td>';
					echo $scenario->getName();
					echo '</td>';
					echo '<td> {{Scénario}}';
					echo '</td>';
					echo '<td>';
					echo '<input type="checkbox" class="imperihomeAttr" data-l1key="scenario_transmit" />';
					echo '</td>';
					echo '<td>';
					echo ' <span class="label label-info" style="font-size : 1em;">DevScene</span>';
					echo '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	</div>

	<div role="tabpanel" class="tab-pane" id="advanced">
		<br/>
		<a class="btn btn-warning pull-right bt_newAdvancedDevice" id=""><i class="fa fa-plus-circle"></i> Ajouter un équipement</a>
		<br><br>
		<table class="table table-bordered table-condensed tablesorter" id="cmdListAdvanced">
			<thead>
				<tr>
					<th>{{Equipement}}</th>
					<th>{{Type}}</th>
					<th>{{Action}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

	<div role="tabpanel" class="tab-pane" id="brute">
		<br>
		<b>Attention: cet onglet n'est pas mis à jour aprés avoir sauvegardé une modification. Pour mettre à jour aprés avoir fait une modification dans votre configuration, veuillez rafraichir la page.</b><br>
		<br>
		<div class="form-group">
			<label class="col-sm-2 control-label">issConfig</label>
			<div class="col-sm-10" style="overflow: scroll; height: 190px; background-color: #EEEEEE;">
				<?php
				$cache = cache::byKey('issConfig');
				echo $cache->getValue('{}');
					?>
				</div>
			</div>
			<br><br>
			<div class="form-group">
				<label class="col-sm-2 control-label">issAdvancedConfig</label>
				<div class="col-sm-10" style="overflow: scroll; height: 190px; background-color: #EEEEEE;">
					<?php
					$cache = cache::byKey('issAdvancedConfig');
					echo $cache->getValue('{}');
						?>
					</div>
				</div>
				<br><br>
				<div class="form-group">
					<label class="col-sm-2 control-label">issTemplate</label>
					<div class="col-sm-10" style="overflow: scroll; height: 190px; background-color: #EEEEEE;">
						<?php
						$cache = cache::byKey('issTemplate');
						echo $cache->getValue('{}');
							?>
						</div>
					</div>
				</div>

			</div>

			<?php include_file('desktop', 'imperihome', 'js', 'imperihome');?>

			<script>
			loadConf();
			loadAdvancedConf();
			</script>