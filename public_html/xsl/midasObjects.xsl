<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns="http://www.heritage-standards.org.uk/midas/schema/2.0" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xsi:schemaLocation="http://www.heritage-standards.org.uk/midas/schema/2.0 http://www.heritage-standards.org.uk/midas/schema/2.0/midas_object.xsd"
				xml:lang="en"
                >
                
    <xsl:output method="xml" indent="yes" encoding="utf-8" />

	<xsl:param name="url">
		<xsl:value-of select="'http://finds.org.uk/database/artefacts/record/id/'" />
	</xsl:param> 
	
	<xsl:param name="denomUrl">
		<xsl:value-of select="'http://finds.org.uk/database/terminology/denominations/denomination/id/'" />
	</xsl:param>
	
	<xsl:param name="bmThes">
		<xsl:value-of select="'http://collection.britishmuseum.org/id/thesauri/'" />
	</xsl:param>
	
	<xsl:param name="thumb">
		<xsl:value-of select="'http://finds.org.uk/images/thumbnails/'" />
	</xsl:param>
	
	<xsl:param name="images">
		<xsl:value-of select="'http://finds.org.uk/images/'" />
	</xsl:param>
	
	<xsl:param name="nomismaUrl">
		<xsl:value-of select="'http://nomisma.org/id/'" />
	</xsl:param>
	
	<xsl:param name="language">
		<xsl:value-of select="'en'" />
	</xsl:param> 
	
	<xsl:template match="/">
		<objects>
			<xsl:apply-templates select="//artefact" />
		</objects>
	</xsl:template>
	
	<xsl:template match="//artefact">
	<object>
		<rights>
		<copyright>
			<holder>The Portable Antiquities Scheme</holder>
		</copyright>
		<accessrights>
			<conditions>CC BY-SA</conditions>
		</accessrights>
		<reproductionrights>
				<statement></statement>
				<contact>info@finds.org.uk</contact>
		</reproductionrights>
		</rights>
		<recordmetadata>
			<created>
				<createdon><xsl:value-of select="created" /></createdon>
				<createdby>
					<appellation>
						<name><xsl:value-of select="fullname" /></name>
						<identifier namespace="PAS"><xsl:value-of select="username" /></identifier>
					</appellation>
				</createdby>
			</created>
			<lastupdated>
				<lastupdatedon><xsl:value-of select="updated" /></lastupdatedon>
				<lastupdatedby>
					<appellation>
						<name><xsl:value-of select="fullnameUpdate" /></name>
						<identifier namespace="PAS"><xsl:value-of select="usernameUpdate" /></identifier>
					</appellation>
				</lastupdatedby>
			</lastupdated>
		</recordmetadata>
	<appellation>
		<identifier namespace="PAS"><xsl:value-of select="old_findID" /></identifier>
		<uri><xsl:value-of select="$url"/><xsl:value-of select="id" /></uri>
	</appellation>
	<character>
		<objecttype certainty="certain"><xsl:value-of select="objecttype" /></objecttype>
	<descriptions>
		<description>
			<full><xsl:value-of select="description" /></full>
		</description>
	</descriptions>
	<manufacture>
		<materials>
			<material><xsl:value-of select="primaryMaterial" /></material>
			<material><xsl:value-of select="secondaryMaterial" /></material>
		</materials>
		<technique><xsl:value-of select="manufacture" /></technique>
		<temporal>
			<span>
				<display>
					<appellation type="period"><xsl:value-of select="broadperiod" /></appellation>
				</display>
				<start>
					<appellation type="date" qualifier="circa"><xsl:value-of select="numdate1" /></appellation>
				</start>
				<end>
					<appellation type="date" qualifier="circa"><xsl:value-of select="numdate2" /></appellation>
				</end>
			</span>
		</temporal>
	</manufacture>
	<measurements>
		<measurement units="mm" type="thickness"><xsl:value-of select="thickness" /></measurement>
		<measurement units="mm" type="diameter"><xsl:value-of select="diameter" /></measurement>
		<measurement units="mm" type="width"><xsl:value-of select="width" /></measurement>
		<measurement units="mm" type="height"><xsl:value-of select="height" /></measurement>
		<measurement units="g"  type="weight"><xsl:value-of select="weight" /></measurement>
	</measurements>
	<decorations>
		<decoration type="inscription">
		<xsl:value-of select="inscription" />
		</decoration>
		<decoration><xsl:value-of select="decoration" /></decoration>
	</decorations>
	</character>
	<condition>
		<completeness><xsl:value-of select="completeness" /></completeness>
	</condition>
	<activities>
		<activity type="recording">
	<temporal>
		<span>
			<start>
				<appellation type="datetime"><xsl:value-of select="created" /></appellation>
			</start>
		</span>
	</temporal>
	</activity>
	</activities>
	<discovery>
	<spatial>
		<place>
		<namedplace>
			<location type="county" namespace="EH_CDP98"><xsl:value-of select="county" /></location>
			<location type="district" namespace="EH_CDP98"><xsl:value-of select="district" /></location>
			<location type="parish" namespace="EH_CDP98"><xsl:value-of select="parish" /></location>
			<location type="knownas" namespace="PAS"><xsl:value-of select="knownas" /></location>
			<gridref namespace="OSGB36"><xsl:value-of select="fourFigure" /></gridref>
		</namedplace>
		</place>
	</spatial>
	<temporal>
		<span>
			<start>
				<appellation type="date" qualifier="exactly"><xsl:value-of select="datefound1" /></appellation>
			</start>
			<end>
				<appellation type="date" qualifier="exactly"><xsl:value-of select="datefound2" /></appellation>
			</end>
		</span>
	</temporal>
	<method><xsl:value-of select="discmethod" /></method>
	<circumstance><xsl:value-of select="disccircum" /></circumstance>
	</discovery>
	</object>
	</xsl:template>
</xsl:stylesheet>