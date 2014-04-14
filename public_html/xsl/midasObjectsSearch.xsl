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
	
		
	<xsl:param name="thumb">
		<xsl:value-of select="'http://finds.org.uk/images/thumbnails/'" />
	</xsl:param>
	
	<xsl:param name="images">
		<xsl:value-of select="'http://finds.org.uk/images/'" />
	</xsl:param>
	
		
	<xsl:param name="language">
		<xsl:value-of select="'en'" />
	</xsl:param> 
	<xsl:template match="/">
		<objects>
			<xsl:apply-templates select="//results" />
		</objects>
	</xsl:template>
	
	<xsl:template match="//results">
		<xsl:for-each select="//results/result">
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
			<material><xsl:value-of select="materialTerm" /></material>
			<xsl:if test="secondaryMaterialTerm != ''">
			<material><xsl:value-of select="secondaryMaterialTerm" /></material>
			</xsl:if>
		</materials>
		<technique><xsl:value-of select="manufactureTerm" /></technique>
		<temporal>
			<span>
				<display>
					<appellation type="period"><xsl:value-of select="broadperiod" /></appellation>
				</display>
				<start>
					<appellation type="date" qualifier="circa"><xsl:value-of select="fromdate" /></appellation>
				</start>
				<end>
					<appellation type="date" qualifier="circa"><xsl:value-of select="todate" /></appellation>
				</end>
			</span>
		</temporal>
	</manufacture>
	<measurements>
		<xsl:if test="thickness != ''">
		<measurement units="mm" type="thickness"><xsl:value-of select="thickness" /></measurement>
		</xsl:if>
		<xsl:if test="diameter != ''">
		<measurement units="mm" type="diameter"><xsl:value-of select="diameter" /></measurement>
		</xsl:if>
		<xsl:if test="width != ''">
		<measurement units="mm" type="width"><xsl:value-of select="width" /></measurement>
		</xsl:if>
		<xsl:if test="height != ''">
		<measurement units="mm" type="height"><xsl:value-of select="height" /></measurement>
		</xsl:if>
		<xsl:if test="weight != ''">
		<measurement units="g"  type="weight"><xsl:value-of select="weight" /></measurement>
		</xsl:if>
	</measurements>
	<decorations>
		<xsl:if test="inscription != ''">
		<decoration type="inscription"><xsl:value-of select="inscription" /></decoration>
		</xsl:if>
		<xsl:if test="decstyleTerm != ''">
		<decoration><xsl:value-of select="decstyleTerm" /></decoration>
		</xsl:if>
	</decorations>
	</character>
	<xsl:if test="completenessTerm != ''">
	<condition>
		<completeness><xsl:value-of select="completenessTerm" /></completeness>
	</condition>
	</xsl:if>
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
			<xsl:if test="county != ''">
			<location type="county" namespace="EH_CDP98"><xsl:value-of select="county" /></location>
			</xsl:if>
			<xsl:if test="district != ''">
			<location type="district" namespace="EH_CDP98"><xsl:value-of select="district" /></location>
			</xsl:if>
			<xsl:if test="parish != ''">
			<location type="parish" namespace="EH_CDP98"><xsl:value-of select="parish" /></location>
			</xsl:if>
			<xsl:if test="knownas != ''">
			<location type="knownas" namespace="PAS"><xsl:value-of select="knownas" /></location>
			</xsl:if>
			<xsl:if test="fourFigure != ''">
			<gridref namespace="OSGB36"><xsl:value-of select="fourFigure" /></gridref>
			</xsl:if>
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
	<method><xsl:value-of select="discoveryMethod" /></method>
	<!--  <circumstance><xsl:value-of select="disccircum" /></circumstance> -->
	</discovery>
	</object>
	</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>